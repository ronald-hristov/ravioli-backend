<?php declare(strict_types=1);

namespace App\Service;

class FileService
{
    /**
     * @var string
     */
    protected $rootPath;

    const IMG_EXT_MAP = [
        'image/png' => 'png',
        'image/jpg' => 'jpg',
        'image/jpeg' => 'jpg',
    ];

    /**
     * FileService constructor.
     * @param string $rootPath
     */
    public function __construct(string $rootPath)
    {
        $this->rootPath = $rootPath;
    }

    protected function validateImgType(\Slim\Http\UploadedFile $file)
    {
        return isset(self::IMG_EXT_MAP[$file->getClientMediaType()]);
    }

    public function moveImgToArticleFolder(\Slim\Http\UploadedFile $file)
    {
        $filename = uniqid();

        if (!$this->validateImgType($file)) {
            throw new \RuntimeException('Use .png or .jpg files');
        }
        $ext = self::IMG_EXT_MAP[$file->getClientMediaType()];

        // TODO check if filename exists
        $relativePath = sprintf('/img/article/%s.%s', $filename, $ext);
        $targetPath = sprintf('%s/public%s', $this->rootPath, $relativePath);
        $file->moveTo($targetPath);

        return $relativePath;
    }
}