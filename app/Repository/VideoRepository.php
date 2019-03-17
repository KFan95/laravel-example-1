<?php

namespace App\Repository;


use App\Models\User;
use App\Models\Video;

class VideoRepository
{
    /**
     * @var Video
     */
    private $model;

    public function __construct()
    {
        $this->model = new Video();
    }

    /**
     * @param User $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function findWIthUser(User $user)
    {
        return $this->model::query()->where('user_id', $user->id)
            ->orderByDesc('created_at');
    }

    /**
     * @param User $user
     * @param int  $id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function findById(User $user, int $id)
    {
        $query = $this->findWIthUser($user)
            ->where('id', $id);

        return $query;
    }

    /**
     * @param User       $user
     * @param bool|array $status
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function find(User $user, $status = false)
    {
        $query = $this->findWIthUser($user);

        if ($status !== false) {
            if (is_array($status)) {
                $query->whereIn('status', $status);
            } else {
                $query->where('status', $status);
            }
        }

        return $query;
    }

    public function create(User $user, array $data = []) {
        $data['user_id'] = $user->id;

        $video = new Video($data);
        $video->save();

        return $video;
    }
}
