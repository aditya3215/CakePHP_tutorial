<?php 

class Post extends AppModel {

    // validation function for title and description: Not empty (Server side Validation)
    public $validate = array(
        'title' => array(
            'rule' => 'notBlank',
            'message' => 'Title cannot be empty'
        ),
        'body' => array(
            'rule' => 'notBlank',
            'message' => 'Body cannot be empty'
        )
    );

    // method to check if the post id is owned by user id.
    private function isOwnedBy($post, $user) {
        return $this->field('id', array('id' => $post, 'user_id' => $user)) !== false;
    }
}
?>