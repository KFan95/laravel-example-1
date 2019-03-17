<?php

namespace App\Service;


use App\Jobs\ProcessVideo;
use App\Models\User;
use App\Models\Video;
use App\Repository\VideoRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VideoService
{
    /**
     * @var VideoRepository
     */
    private $videoRepository;

    /**
     * VideoService constructor.
     *
     * @param VideoRepository $videoRepository
     */
    public function __construct(VideoRepository $videoRepository)
    {
        $this->videoRepository = $videoRepository;
    }

    /**
     * @param User                          $user
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return string
     * @throws \Exception
     */
    public function uploadVideo(User $user, \Illuminate\Http\UploadedFile $file)
    {
        if (!$file instanceof \Illuminate\Http\UploadedFile) {
            throw new \Exception('Wrong parameters');
        }

        if (!$file->isValid()) {
            throw new \Exception('File didn\'t upload');
        }

        if (!in_array($file->extension(), ['mp4'])) {
            throw new \Exception('Unsupported file uploaded');
        }

        $fileName = $file->hashName();

        $subPath = ['video'];
        for ($i = 0; $i < 3; $i++) {
            $subPath[] = Str::substr($fileName, $i * 3, 3);
        }

        try {
            $video = $this->videoRepository->create($user, [
                'name' => $file->getClientOriginalName()
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            throw new \Exception('Internal server error');
        }

        $videoPath = $file->storeAs(implode('/', $subPath), $fileName, 'public');

        if ($videoPath === false) {
            throw new \Exception('File saving error');
        }

        $video->src = $videoPath;

        try {
            $video->status = Video::STATUS_PROCESS;
            $video->save();
        } catch (\Exception $e) {
            Log::error($e);
            throw new \Exception('Internal server error');
        }

        ProcessVideo::dispatch($video);

        return $video;
    }
}
