<?php

require_once(__DIR__ . '/router.php');


get('/', function () {
   require_once(__DIR__ . '/views/events.php');
});

get('/event/error/:display_message', function ($display_message) {
   require_once(__DIR__ . '/bridges/bridge_go_to_start.php');
   require_once(__DIR__ . '/views/events.php');
});


##############################
##############################

get('/events', function () {
   require_once(__DIR__ . '/views/events.php');
});
get('/events/:display_message', function ($display_message) {
   require_once(__DIR__ . '/bridges/bridge_go_to_start.php');
   require_once(__DIR__ . '/views/events.php');
});
get('/forum', function () {
   require_once(__DIR__ . '/views/forum.php');
});
get('/forum/:display_message', function ($display_message) {
   require_once(__DIR__ . '/bridges/bridge_go_to_start.php');
   require_once(__DIR__ . '/views/forum.php');
});

get('/admin', function () {
   require_once(__DIR__ . '/apis/api_display_my_comments.php');
   require_once(__DIR__ . '/views/admin.php');
});

get('/user/:uuid', function ($uuid) {
   require_once(__DIR__ . '/views/user.php');
});

get('/posts/:thread_id', function ($thread_id) {
   require_once(__DIR__ . '/apis/api_display_comments.php');
   require_once(__DIR__ . '/views/posts.php');
});

get('/posts/:thread_id/:display_message', function ($thread_id, $display_message) {
   require_once(__DIR__ . '/apis/api_display_comments.php');
   require_once(__DIR__ . '/views/posts.php');
});
get('/events/succes/:display_message', function ($display_message) {
   require_once(__DIR__ . '/bridges/bridge_go_to_start.php');
   require_once(__DIR__ . '/views/events.php');
});
get('/events/:category/:genre', function ($category, $genre) {
   require_once(__DIR__ . '/views/events.php');
   require_once(__DIR__ . '/apis/api_display_events.php');
});
get('/events/event/:event_id', function ($event_id) {
   require_once(__DIR__ . '/views/events.php');
});

get('/signup', function () {
   require_once(__DIR__ . '/views/signup.php');
});
get('/test_comments', function () {
   require_once(__DIR__ . '/apis/api_display_comments.php');
});

get('/login', function () {
   require_once(__DIR__ . '/views/login.php');
});


get('/login/:display_error', function ($display_error) {
   require_once(__DIR__ . '/views/login.php');
});
get('/signup/:display_error', function ($display_error) {
   require_once(__DIR__ . '/views/signup.php');
});


get('/admin/note/:note', function ($note) {
   require_once(__DIR__ . '/views/admin.php');
});

get('/admin/:display_message', function ($display_message) {
   require_once(__DIR__ . '/views/admin.php');;
});


get('/logout', function () {
   require_once(__DIR__ . '/bridges/bridge_logout.php');
});
get('/event/upload/:img/:type', function ($img, $type) {
   $image = $img . '.' . $type;
   require_once(__DIR__ . '/bridges/bridge_go_to_start.php');
   require_once(__DIR__ . '/apis/api_add_event.php');
});


##############################
############ POST ############
##############################

post('/login', function () {
   require_once(__DIR__ . '/bridges/bridge_login.php');
});

post('/signup', function () {
   require_once(__DIR__ . '/bridges/bridge_signup.php');
});

post('/admin', function () {
   require_once(__DIR__ . '/bridges/bridge_update_user.php');
});

post('/admin/image/:user_id', function ($user_id) {
   require_once(__DIR__ . '/bridges/bridge_upload_file.php');
});

post('/post/comment/:thread_id', function ($thread_id) {
   require_once(__DIR__ . '/bridges/bridge_post_comment.php');
});

post('/admin/delete/:comment_id/:user_id', function ($comment_id, $user_id) {
   require_once(__DIR__ . '/apis/api_delete_comment.php');
});

post('/post/delete/:comment_id/:user_id/:thread_id', function ($comment_id, $user_id, $thread_id) {
   require_once(__DIR__ . '/apis/api_delete_comment.php');
});
post('/topic/delete/:thread_id/:user_id', function ($thread_id, $user_id) {
   require_once(__DIR__ . '/apis/api_delete_thread.php');
});

post('/topic/update/:thread_id/:user_id/:thread_done', function ($thread_id, $user_id, $thread_done) {
   require_once(__DIR__ . '/apis/api_mark_thread.php');
});

post('/forum', function () {
   require_once(__DIR__ . '/apis/api_post_thread.php');
});

post('/events/new/:event', function ($event) {
   require_once(__DIR__ . '/bridges/bridge_upload_file.php');
});

post('/events/delete/:event_id/:user_id', function ($event_id, $user_id) {
   require_once(__DIR__ . '/apis/api_delete_event.php');
});

// ##############################

// For GET or POST
any('/404', function () {
   echo 'Not found';
});