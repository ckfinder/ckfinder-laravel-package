<?php

/*
 * CKFinder
 * ========
 * https://ckeditor.com/ckfinder/
 * Copyright (c) 2007-2021, CKSource - Frederico Knabben. All rights reserved.
 *
 * The software, this file and its contents are subject to the CKFinder
 * License. Please read the license.txt file before using, installing, copying,
 * modifying or distribute this file or part of its contents. The contents of
 * this file is part of the Source Code of CKFinder.
 */

namespace CKSource\CKFinder\Filesystem\File;

use CKSource\CKFinder\Backend\Backend;
use CKSource\CKFinder\CKFinder;
use CKSource\CKFinder\Error;
use CKSource\CKFinder\Exception\AccessDeniedException;
use CKSource\CKFinder\Exception\InvalidUploadException;
use CKSource\CKFinder\Filesystem\Folder\WorkingFolder;
use CKSource\CKFinder\Utils;
use Symfony\Component\HttpFoundation\File\UploadedFile as UploadedFileBase;
use Symfony\Component\Mime\MimeTypes;

/**
 * The UploadedFile class.
 *
 * Represents uploaded file
 */
class UploadedFile extends File
{
    /**
     * A Symfony UploadedFile object.
     *
     * @var UploadedFileBase
     */
    protected $uploadedFile;

    /**
     * A WorkingFolder object pointing to the folder where the file is uploaded.
     *
     * @var WorkingFolder
     */
    protected $workingFolder;

    /**
     * Temporary path for the uploaded file.
     *
     * @var string
     */
    protected $tempFilePath;

    /**
     * Constructor.
     *
     * @throws \Exception if file upload failed
     */
    public function __construct(UploadedFileBase $uploadedFile, CKFinder $app)
    {
        parent::__construct($uploadedFile->getClientOriginalName(), $app);

        $this->uploadedFile = $uploadedFile;
        $this->workingFolder = $app['working_folder'];

        $this->tempFilePath = tempnam($this->config->get('tempDirectory'), 'ckf');
        $pathinfo = pathinfo($this->tempFilePath);

        if (!is_writable($this->tempFilePath)) {
            throw new InvalidUploadException('The temporary folder is not writable for CKFinder');
        }

        try {
            $uploadedFile->move($pathinfo['dirname'], $pathinfo['basename']);
        } catch (\Exception $e) {
            $errorMessage = $uploadedFile->getErrorMessage();
            switch ($uploadedFile->getError()) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new InvalidUploadException($errorMessage, Error::UPLOADED_TOO_BIG, [], $e);
                case UPLOAD_ERR_PARTIAL:
                case UPLOAD_ERR_NO_FILE:
                    throw new InvalidUploadException($errorMessage, Error::UPLOADED_CORRUPT, [], $e);
                case UPLOAD_ERR_NO_TMP_DIR:
                    throw new InvalidUploadException($errorMessage, Error::UPLOADED_NO_TMP_DIR, [], $e);
                case UPLOAD_ERR_CANT_WRITE:
                case UPLOAD_ERR_EXTENSION:
                    throw new AccessDeniedException($errorMessage, [], $e);
            }
        }
    }

    /**
     * Destructor: Removes the temporary file, if required.
     */
    public function __destruct()
    {
        if (file_exists($this->tempFilePath)) {
            unlink($this->tempFilePath);
        }
    }

    /**
     * Checks if the file was uploaded properly.
     *
     * @return bool `true` if upload is valid
     */
    public function isValid()
    {
        return $this->uploadedFile && $this->tempFilePath && is_readable($this->tempFilePath) && is_writable($this->tempFilePath);
    }

    /**
     * Sanitizes current file name using options set in Config.
     */
    public function sanitizeFilename()
    {
        $this->fileName = static::secureName(
            $this->fileName,
            $this->config->get('disallowUnsafeCharacters'),
            $this->config->get('forceAscii')
        );

        $resourceType = $this->workingFolder->getResourceType();

        if ($this->config->get('checkDoubleExtension')) {
            $this->fileName = Utils::replaceDisallowedExtensions($this->fileName, $resourceType);
        }
    }

    /**
     * Checks if the file extension is allowed in the target folder.
     *
     * @return bool `true` if an extension is allowed in the target folder
     */
    public function hasAllowedExtension()
    {
        $ext = false === strpos($this->fileName, '.')
            ? null
            : $this->getExtension();

        return $this->workingFolder->getResourceType()->isAllowedExtension($ext);
    }

    /**
     * @copydoc File::autorename()
     *
     * @param mixed $path
     */
    public function autorename(Backend $backend = null, $path = '')
    {
        return parent::autorename($this->workingFolder->getBackend(), $this->workingFolder->getPath());
    }

    /**
     * Checks if the file was renamed.
     *
     * @return bool `true` if the file was renamed
     */
    public function wasRenamed()
    {
        return $this->fileName !== $this->uploadedFile->getClientOriginalName();
    }

    /**
     * Check if the current file name is defined as hidden in configuration settings.
     *
     * @return bool `true` if the file name is hidden
     */
    public function isHiddenFile()
    {
        return $this->workingFolder->getBackend()->isHiddenFile($this->fileName);
    }

    /**
     * Returns the upload error.
     *
     * If the upload was successful, the `UPLOAD_ERR_OK` constant is returned.
     * Otherwise one of the other `UPLOAD_ERR_XXX` constants is returned.
     *
     * @return int upload error
     */
    public function getError()
    {
        return $this->uploadedFile->getError();
    }

    /**
     * Returns the upload error message.
     *
     * @return string upload error
     */
    public function getErrorMessage()
    {
        return $this->uploadedFile->getErrorMessage();
    }

    /**
     * Returns uploaded file contents.
     *
     * @return string uploaded file data
     */
    public function getContents()
    {
        return file_get_contents($this->tempFilePath);
    }

    /**
     * Returns contents stream for the uploaded file.
     *
     * @return resource
     */
    public function getContentsStream()
    {
        return fopen($this->tempFilePath, 'r');
    }

    /**
     * Returns uploaded file size in bytes.
     *
     * @return int file size in bytes
     */
    public function getSize()
    {
        clearstatcache();

        return filesize($this->tempFilePath);
    }

    /**
     * Returns uploaded file MIME type.
     *
     * @return string
     */
    public function getMimeType()
    {
        return MimeTypes::getDefault()->guessMimeType($this->tempFilePath);
    }

    /**
     * Detects HTML in the first KB to prevent against a potential security issue with
     * IE/Safari/Opera file type auto detection bug.
     * Returns `true` if a file contains insecure HTML code at the beginning.
     *
     * @return bool `true` if the uploaded file contains HTML in the first 1024 bytes
     */
    public function containsHtml()
    {
        $fp = fopen($this->tempFilePath, 'r');
        $chunk = fread($fp, 1024);
        fclose($fp);

        return Utils::containsHtml($chunk);
    }

    /**
     * Checks if a file with the current extension is allowed to contain any HTML/JS.
     *
     * @return bool `true` if a file is allowed to contain HTML chunks
     */
    public function isAllowedHtmlFile()
    {
        return \in_array(strtolower($this->getExtension()), $this->config->get('htmlExtensions'), true);
    }

    /**
     * Checks if the file is a valid image.
     *
     * Internally `getimagesize` is used for validation.
     *
     * @return bool `true` if the file is a valid image
     */
    public function isValidImage()
    {
        if (false === @getimagesize($this->tempFilePath)) {
            return false;
        }

        return true;
    }

    /**
     * Saves the data as new file contents.
     *
     * @param string $data new file contents
     */
    public function save($data)
    {
        file_put_contents($this->tempFilePath, $data);
    }
}
