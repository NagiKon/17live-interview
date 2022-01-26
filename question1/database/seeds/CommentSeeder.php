<?php

use App\Models\Post;
use App\Models\Comment;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Faker\Generator as Faker;

class CommentSeeder extends Seeder
{
    private $faker;

    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            Comment::truncate();
            $this->createComments();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * 針對每筆 Post，創建 comments 的資料
     *
     * @param  int $num 每筆 post 對應的 comment 數量。預設 5 筆。
     * @return void
     */
    public function createComments(int $num = 5): void
    {
        $posts = Post::all('id');
        foreach ($posts as $post) {
            for ($i = 0; $i < $num; $i++) {
                $comment = new Comment();

                $comment->post_id = $post->id;
                $comment->message = $this->faker->realText($maxNbChars = 300, $indexSize = 3);

                $comment->save();
            }
        }
    }
}
