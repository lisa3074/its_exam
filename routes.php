<?php

require_once(__DIR__ . '/router.php');


get('/', function(){
   require_once(__DIR__ . '/views/events.php');

   
});


##############################
##############################

get('/events', function(){
   require_once(__DIR__ . '/views/events.php');
   
});
get('/forum', function(){
   require_once(__DIR__ . '/views/forum.php');
   
});

get('/admin', function(){
   require_once(__DIR__ . '/apis/api_display_my_comments.php');
   require_once(__DIR__ . '/views/admin.php');
});

get('/user/:uuid', function($uuid){
   require_once(__DIR__ . '/views/user.php');
});

get('/posts/:thread_id', function($thread_id){
   require_once(__DIR__ . '/apis/api_display_comments.php');
   require_once(__DIR__ . '/views/posts.php');
   
});

get('/posts/:thread_id/:display_message', function($thread_id, $display_message){
   require_once(__DIR__ . '/apis/api_display_comments.php');
   require_once(__DIR__ . '/views/posts.php');
   
});
get('/events/:category', function($category){
   require_once(__DIR__ . '/views/events.php');
   require_once(__DIR__ . '/apis/api_display_events.php');

});

get('/index/:display_error', function($display_error){
   require_once(__DIR__ . '/views/index.php');

});

get('/signup', function(){
   require_once(__DIR__ . '/views/signup.php');

});
get('/test_comments', function(){
   require_once(__DIR__ . '/apis/api_display_comments.php');

});

get('/login', function(){
   require_once(__DIR__ . '/views/login.php');
});


get('/login/:display_error', function($display_error){
   require_once(__DIR__ . '/views/login.php');

});
get('/signup/:display_error', function($display_error){
   require_once(__DIR__ . '/views/signup.php');

});


get('/admin/note/:note', function($note){
   require_once(__DIR__ . '/views/admin.php');

});

get('/admin/:display_message', function($display_message){
   require_once(__DIR__ . '/views/admin.php');
   //require_once(__DIR__ . '/apis/api_display_my_comments.php');

});
/* get('/posts/:thread_id/:display_message', function($thread_id, $display_message){
   require_once(__DIR__ . "/views/posts.php");

}); */

get('/logout', function(){
   require_once(__DIR__ . '/bridges/bridge_logout.php');

});

##############################
############ POST ############
##############################

post('/login', function(){
   require_once(__DIR__ . '/apis/api_login.php');

});
post('/signup', function(){
   require_once(__DIR__ . '/apis/api_signup.php');

});
post('/admin', function(){
   //echo 'Hello';
  require_once(__DIR__ . '/apis/api_save_note.php');
  require_once(__DIR__ . '/views/admin.php');

});
post('/posts/comment/:thread_id', function($thread_id){
   //echo 'Hello';
  require_once(__DIR__ . '/apis/api_save_comment.php');
  require_once(__DIR__ . '/views/posts.php');

});
post('/admin/delete/:comment_id/:user_id', function($comment_id, $user_id){
   //echo 'Hello';
  require_once(__DIR__ . '/apis/api_delete_comment.php');
  require_once(__DIR__ . '/views/admin.php');

});
post('/show_note', function(){
   //echo 'Hello';
  require_once(__DIR__ . '/apis/api_display_note.php');
  require_once(__DIR__ . '/views/admin.php');

});


get('/error', function(){
   require_once(__DIR__ . '/views/index.php');
   echo 'Wrong';

});


// ##############################

// For GET or POST
any('/404', function(){
  echo 'Not found';
});