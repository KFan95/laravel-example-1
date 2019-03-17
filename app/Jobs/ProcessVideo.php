<?php

namespace App\Jobs;

use App\Models\Video;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProcessVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Video
     */
    private $video;

    /**
     * Create a new job instance.
     *
     * @param Video $video
     *
     * @return void
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $disk = \Storage::disk('public');

        $fullVideoPath = $disk->path($this->video->src);

        try {
            $duration = FFProbe::create([
                'ffmpeg.binaries' => config('video.ffmpeg'),
                'ffprobe.binaries' => config('video.ffprobe'),
            ])->format($fullVideoPath)->get('duration');

            $ffmpeg = FFMpeg::create([
                'ffmpeg.binaries' => config('video.ffmpeg'),
                'ffprobe.binaries' => config('video.ffprobe'),
            ]);
            $video = $ffmpeg->open($fullVideoPath);

            $videoSrc = $this->video->src;

            $thumbSrc = preg_replace('/^([^\/]+)/i', '$1_thumb', $videoSrc);
            $thumbSrc = preg_replace('/\.([\w]+)$/i', '.jpg', $thumbSrc);

            $thumbFullSrc = $disk->path($thumbSrc);

            mkdir(dirname($thumbFullSrc), 0777, true);

            // need resize
            $video->frame(TimeCode::fromSeconds($duration > 2 ? 2 : 0))->save($thumbFullSrc);

            $this->video->duration = ceil($duration);
            $this->video->preview = $thumbSrc;
            $this->video->status = Video::STATUS_PROCESSED;

            $this->video->save();
        } catch (\Exception $e) {
            Log::error($e);

            $this->release(10);
        }
    }
}
