<?php

namespace App\Services;

use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use phpDocumentor\Reflection\Types\Self_;

class UploadService
{
    private $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd'];
    private $videoExtensions = ['flv', 'mp4', 'm3u8', 'ts', '3gp', 'mov', 'avi', 'wmv'];
    private function uploadFile($file, $originalName = false, $thumb = true)
    {
        $random = Str::random(6);
        if($originalName) {
            $fileName = preg_replace("/\s/", "-", $file->getClientOriginalName());
        } else {
            $fileName = "{$random}-" . time() . "." . $file->getClientOriginalExtension();
        }
        // upload videos
        $filePath = Storage::disk('public')->putFileAs('uploads', $file, $fileName);

        // upload images
        if(in_array($file->getClientOriginalExtension(), $this->imageExtensions) && $thumb) {
            $thumpPath = Storage::disk('public')->putFileAs('uploads/thumbnails', $file, $fileName);
            $img = Image::make($file)
                ->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->stream();
            Storage::disk('public')->put($thumpPath, $img);
            return ["filePath" => url(Storage::url($filePath)), "thumbnail" => url(Storage::url($thumpPath)), "name" => Storage::url($filePath)];
        }
        return ["filePath" => url(Storage::url($filePath)), "name" => Storage::url($filePath)];
    }

    public function upload(Request $request) {

        $request->validate([
            "file" => "required"
        ]);
        return $this->uploadFile($request->file);
    }

    public function uploadFiles(Request $request)
    {
        $request->validate([ "files" => "required" ]);

        $files = [];
        foreach ($request->files->get("files") as $file) {
            $files[] = $this->uploadFile($file, true)['filePath'];
        }
        return $files;
    }

    public function getImageThumbnailLink($imageLink)
    {
        $imgArr = explode('/', $imageLink);
        $last = end($imgArr);
        array_pop($imgArr);
        $imgArr[] = 'thumbnails';
        $imgArr[] = $last;
        if(Storage::disk('public')->exists("uploads/thumbnails/{$last}")) {
            return implode('/', $imgArr);
        }
        return null;
    }

    public function getUploads(Request $request)
    {
        $request->validate([ "page" => "required|int" ]);

        $fullDirName = "public/uploads";
        $files = Storage::files($fullDirName);

        $files = array_map(function ($item) use ($fullDirName)
        {
            return [
                "name" => str_replace($fullDirName . "/" , "", $item),
                "url" => Url::to('') . Storage::url($item)
            ];
        }, $files);
        array_shift($files);
        return array_slice($files, (($request->page - 1) * 8), 8);
    }

    public function makeVideoThumb($movie, $sec)
    {
        $ffmpeg = \FFMpeg\FFMpeg::create([
            'ffmpeg.binaries'  => '/usr/local/bin/ffmpeg',
            'ffprobe.binaries' => '/usr/local/bin/ffprobe'
        ]);
//        dd(Storage::disk('public')->path($movie), $movie);
        $video = $ffmpeg->open(Storage::disk('public')->path($movie));
        $frame = $video->frame(TimeCode::fromSeconds($sec));
        $thumbnail = rand(10000, 99999) . "-thumbnail.png";
        $thumbPath = Storage::disk("public")->path("uploads/thumbnails/$thumbnail");
        $frame->save($thumbPath);
        return url("storage/uploads/thumbnails/{$thumbnail}");
    }

}
