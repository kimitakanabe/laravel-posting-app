<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;

class PostTest extends TestCase
{
    use RefreshDatabase;

    // 未ログインのユーザーは投稿一覧ページにアクセスできない
    public function test_guest_cannot_access_posts_index()
    {
        $response = $this->get(route('posts.index'));

        // 	リダイレクトがあったことを検証する。
        $response->assertRedirect(route('login'));
    }

    // ログイン済みのユーザーは投稿一覧ページにアクセスできる
    public function test_user_can_access_posts_index()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('posts.index'));

        // 指定したHTTPステータスコード(200=成功)と実際のHTTPステータスコードが一致することを検証する。
        $response->assertStatus(200);
        // 指定したテキストがHTTPレスポンスの本文（HTML内）に含まれていることを検証する。
        $response->assertSee($post->title);
    }

     // 未ログインのユーザーは投稿詳細ページにアクセスできない
     public function test_guest_cannot_access_posts_show()
     {
         $user = User::factory()->create();
         $post = Post::factory()->create(['user_id' => $user->id]);

         $response = $this->get(route('posts.show', $post));

         $response->assertRedirect(route('login'));
     }

     // ログイン済みのユーザーは投稿詳細ページにアクセスできる
     public function test_user_can_access_posts_show()
     {
         $user = User::factory()->create();
         $post = Post::factory()->create(['user_id' => $user->id]);

         $response = $this->actingAs($user)->get(route('posts.show', $post));

         $response->assertStatus(200);

         $response->assertSee($post->title);
     }

    //  未ログインのユーザーは新規投稿ページにアクセスできない
    public function test_guest_cannot_access_posts_create()
    {
        $response = $this->get(route('posts.create'));

        $response->assertRedirect(route('login'));
    }

    // ログイン済みのユーザーは新規投稿ページにアクセスできる
    public function test_user_can_access_posts_create()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('posts.create'));

        $response->assertStatus(200);
    }

    // 未ログインのユーザーは投稿を作成できない
    public function test_guest_cannot_access_posts_store()
    {
        $post = [
            'title' => 'プログラミング学習1日目',
            'content' => '今日からプログラミング学習開始！頑張るぞ！'
        ];
        // POSTリクエストを送信するpost()メソッドでは、第2引数に連想配列を指定することでデータを送信できます。そこで今回のテストでは、作成する投稿データをあらかじめ連想配列として定義しておき、post()メソッドの第2引数に渡しています。
        $response = $this->post(route('posts.store'), $post);

        // 指定したデータがテーブルに存在しないことを検証する
        $this->assertDatabaseMissing('posts', $post);
        $response->assertRedirect(route('login'));
    }

    // ログイン済みのユーザーは投稿を作成できる
    public function test_user_can_access_posts_store()
    {
        $user = User::factory()->create();

        $post = [
            'title' => 'プログラミング学習1日目',
            'content' => '今日からプログラミング学習開始！頑張るぞ！'
        ];

        $response = $this->actingAs($user)->post(route('posts.store'), $post);

        // 指定したデータがテーブルに存在することを検証する。
        $this->assertDatabaseHas('posts', $post);
        $response->assertRedirect(route('posts.index'));
    }

    //未ログインのユーザーが投稿編集ページにアクセスできない
    public function test_guest_cannot_access_posts_edit()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->get(route('posts.edit', $post));

        $response->assertRedirect(route('login'));
    }

    // ログイン済みのユーザーは他人の投稿編集パージにアクセスできない
    public function test_user_cannot_access_others_posts_edit()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();
        $others_post = Post::factory()->create(['user_id' => $other_user->id]);

        $response = $this->actingAs($user)->get(route('posts.edit', $others_post));

        $response->assertRedirect(route('posts.index'));
    }

    // ログイン済みのユーザーは自身の投稿ページにアクセスできる
    public function test_user_can_access_own_posts_edit()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('posts.edit', $post));

        $response->assertStatus(200);
    }

    // 未ログインのユーザーは投稿を更新できない
    public function test_guest_cannot_update_post()
    {
        $user = User::factory()->create();
        $old_post = Post::factory()->create(['user_id' => $user->id]);

        $new_post = [
            'title' => 'プログラミング学習1日目',
            'content' => '今日からプログラミング学習開始！頑張るぞ！'
        ];

        $response = $this->patch(route('posts.update', $old_post), $new_post);

        $this->assertDatabaseMissing('posts', $new_post);
        $response->assertRedirect(route('login'));
    }

    // ログイン済みのユーザーは他人の投稿を更新できない
    public function test_user_cannot_update_others_post()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();
        $others_old_post = Post::factory()->create(['user_id' => $other_user->id]);

        $new_post = [
            'title' => 'プログラミング学習1日目',
            'content' => '今日からプログラミング学習開始！頑張るぞ！'
        ];

        $response = $this->actingAs($user)->patch(route('posts.update', $others_old_post), $new_post);

        $this->assertDatabaseMissing('posts', $new_post);
        $response->assertRedirect(route('posts.index'));
    }

    // ログイン済みのユーザーは自身の投稿を更新できる
    public function test_user_can_update_own_post()
    {
        $user = User::factory()->create();
        $old_post = Post::factory()->create(['user_id' => $user->id]);

        $new_post = [
            'title' => 'プログラミング学習1日目',
            'content' => '今日からプログラミング学習開始！頑張るぞ！'
        ];

        $response = $this->actingAs($user)->patch(route('posts.update', $old_post), $new_post);

        $this->assertDatabaseHas('posts', $new_post);
        $response->assertRedirect(route('posts.show', $old_post));
    }

    //  未ログインのユーザーは投稿を削除できない
    public function test_guest_cannot_destroy_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->delete(route('posts.destroy', $post));

        $this->assertDatabaseHas('posts', ['id' => $post->id]);
        $response->assertRedirect(route('login'));
    }

    // ログイン済み（かつメール認証済み）のユーザーは他人の投稿を削除できない
    public function test_user_cannot_destroy_others_post()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();
        $others_post = Post::factory()->create(['user_id' => $other_user->id]);

        $response = $this->actingAs($user)->delete(route('posts.destroy', $others_post));

        $this->assertDatabaseHas('posts', ['id' => $others_post->id]);
        $response->assertRedirect(route('posts.index'));
    }

    // ログイン済み（かつメール認証済み）のユーザーは自身の投稿を削除できる
    public function test_user_can_destroy_own_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete(route('posts.destroy', $post));

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
        $response->assertRedirect(route('posts.index'));
    }
}

