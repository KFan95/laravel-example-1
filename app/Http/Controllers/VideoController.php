<?php
/**
 * Created by PhpStorm.
 * User: kfan9
 * Date: 15.03.2019
 * Time: 23:40
 */

namespace App\Http\Controllers;


use App\Repository\VideoRepository;
use App\Service\VideoService;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * @var VideoRepository
     */
    private $videoRepository;

    /**
     * @var VideoService
     */
    private $videoService;

    /**
     * VideoController constructor.
     *
     * @param VideoRepository $videoRepository
     * @param VideoService    $videoService
     */
    public function __construct(VideoRepository $videoRepository, VideoService $videoService)
    {
        $this->videoRepository = $videoRepository;
        $this->videoService = $videoService;
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            $videos = $this->videoRepository->find(\Auth::user())->get()->all();

            return response()->json([
                'videos' => $videos
            ]);
        } else {
            return view('video.list', [
                'videos' => []
            ]);
        }
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        $response = array();

        try {
            $file = $request->file('file');

            $video = $this->videoService->uploadVideo(\Auth::user(), $file);

            $response = array(
                'result' => true,
                'video' => $video
            );
        } catch (\Exception $e) {
            $response = array(
                'result' => false,
                'message' => $e->getMessage()
            );
        }

        return response()->json($response);
    }

    /**
     * @param Request $request
     * @param int     $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function state(Request $request, int $id) {
        $video = $this->videoRepository->findById(\Auth::user(), $id)->firstOrFail();

        return response()->json([
            'status' => $video->status,
            'video' => $video->status != 'process' ? $video : null
        ]);
    }

    /**
     * @param Request $request
     * @param int     $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(Request $request, int $id)
    {
        $video = $this->videoRepository->findById(\Auth::user(), $id)->firstOrFail();

        if ($request->expectsJson()) {
            return response()->json([
                'video' => $video
            ]);
        } else {
            return view('video.detail', [
                'video' => $video
            ]);
        }
    }
}
