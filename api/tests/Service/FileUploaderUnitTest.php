<?php

namespace App\Service;

use League\Flysystem\FilesystemOperator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase as FrameworkTestCase;

class FileUploaderUnitTest extends FrameworkTestCase
{
	public function testSuccessUploadBase64File()
	{
		$base64Image =
			"data:image/png;base64,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";

		$data = explode(',', $base64Image);
		/**
		 * @var FilesystemOperator&MockObject&FilesystemOperator
		 */
		$filesystem = $this->getMockBuilder(FilesystemOperator::class)
			->disableOriginalConstructor()
			->getMock();
		$filesystem
			->expects(self::exactly(1))
			->method('write')
			->with($this->isType('string'), base64_decode($data[1]));

		$fileUploader = new FileUploader($filesystem);
		$filename = $fileUploader->uploadBase64File($base64Image);
		$this->assertNotEmpty($filename);
		$this->assertMatchesRegularExpression('/\.[jpeg|png]/', $filename, 'El formato de la imagen no correcto');
	}
}