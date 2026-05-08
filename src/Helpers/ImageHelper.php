<?php
declare(strict_types=1);

final class ImageHelper
{
    public const MAX_SIZE = 3 * 1024 * 1024; // 3 MB
    public const MAX_DIMENSION = 1600;       // px máximo en el lado más largo
    private const MIMES_VALIDOS = ['image/jpeg', 'image/png', 'image/webp'];

    /**
     * Valida y procesa una imagen subida.
     * Devuelve ['contenido' => string, 'mime' => string] o lanza RuntimeException.
     */
    public static function procesarSubida(array $file): array
    {
        if (!isset($file['error']) || is_array($file['error'])) {
            throw new RuntimeException('Subida de archivo inválida.');
        }

        switch ($file['error']) {
            case UPLOAD_ERR_OK: break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No se ha subido ningún archivo.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('El archivo es demasiado grande.');
            default:
                throw new RuntimeException('Error en la subida del archivo.');
        }

        if ($file['size'] > self::MAX_SIZE) {
            throw new RuntimeException('La imagen no puede pesar más de 3 MB.');
        }

        // Detectar mime real (no fiarse del que envía el cliente)
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (!in_array($mime, self::MIMES_VALIDOS, true)) {
            throw new RuntimeException('Solo se permiten JPG, PNG o WebP.');
        }

        // Cargar imagen y redimensionar si es necesario
        $img = self::cargar($file['tmp_name'], $mime);
        if ($img === false) {
            throw new RuntimeException('No se pudo procesar la imagen.');
        }

        $img = self::redimensionar($img);

        // Reconvertir a JPG (más universal y comprimido)
        ob_start();
        imagejpeg($img, null, 85);
        $contenido = ob_get_clean();
        imagedestroy($img);

        return ['contenido' => $contenido, 'mime' => 'image/jpeg'];
    }

    private static function cargar(string $ruta, string $mime)
    {
        return match ($mime) {
            'image/jpeg' => imagecreatefromjpeg($ruta),
            'image/png'  => imagecreatefrompng($ruta),
            'image/webp' => imagecreatefromwebp($ruta),
            default      => false,
        };
    }

    private static function redimensionar($img)
    {
        $w = imagesx($img);
        $h = imagesy($img);

        if ($w <= self::MAX_DIMENSION && $h <= self::MAX_DIMENSION) {
            return $img;
        }

        if ($w > $h) {
            $newW = self::MAX_DIMENSION;
            $newH = (int) ($h * (self::MAX_DIMENSION / $w));
        } else {
            $newH = self::MAX_DIMENSION;
            $newW = (int) ($w * (self::MAX_DIMENSION / $h));
        }

        $resized = imagecreatetruecolor($newW, $newH);
        // Fondo blanco para PNGs con transparencia (al pasar a JPG)
        $white = imagecolorallocate($resized, 255, 255, 255);
        imagefill($resized, 0, 0, $white);
        imagecopyresampled($resized, $img, 0, 0, 0, 0, $newW, $newH, $w, $h);
        imagedestroy($img);

        return $resized;
    }
}
