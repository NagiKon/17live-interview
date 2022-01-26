<?php

use App\Models\Post;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Faker\Generator as Faker;

class PostSeeder extends Seeder
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
            Post::truncate();
            $this->createPosts();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * 創建 posts 表的假資料
     *
     * @param  int $num 要建立的 post 數量，預設 10 筆。
     * @return void
     */
    private function createPosts(int $num = 10): void
    {
        for ($i = 0; $i < $num; $i++) {
            $post = new Post();

            $post->title = $this->faker->text($maxNbChars = 30);
            $post->content = $this->faker->realText($maxNbChars = 500, $indexSize = 5);

            $post->save();
        }
    }
}
