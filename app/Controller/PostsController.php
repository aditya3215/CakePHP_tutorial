<?php 
App::uses('AppController', 'Controller');
// Controller for post on the Front end
class PostsController extends AppController{
    public $helper = array('Html','Form');
    public $components = array('Flash');

    // action for Post Controller
    public function  index(){
        $this->set('posts',$this->Post->find('all'));
    }

    public function view($id) {
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }

        $post = $this->Post->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }
        $this->set('post', $post);
    }

    // function for adding new Blog
    public function add() {
        if ($this->request->is('post')) {
            // Adding user_id to the post Request Data
            $this->request->data['Post']['user_id'] = trim($this->Auth->user('id'));
            
            foreach ($this->request->data['Post'] as $key => $value) {
                if (is_string($value)) {
                    $this->request->data['Post'][$key] = trim($value);
                }
            }
            if ($this->Post->save($this->request->data)) {
                $this->Flash->success(__('Your post has been saved.'));
                return $this->redirect(array('action' => 'index'));
            }
        }
    }
   
    // function for editing a post
    public function edit($id = null) {

        // cheking if the ID is passed to the function
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }
    
        // Storing the post data with the given ID
        $post = $this->Post->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }
    
        // checking if the Request type is POST or PUT
        if ($this->request->is(array('post', 'put'))) {
            $this->Post->id = $id;
            if ($this->Post->save($this->request->data)) {

                // If the blog is saved successfully then show success message
                $this->Flash->success(__('Your post has been updated.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Flash->error(__('Unable to update your post.'));
        }
    
        // if the response is empty then Re-store the previous data.
        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    // function for deleting the Blog
    public function delete($id) {

        // fetching ID from the URL
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
        if ($this->Post->delete($id)) {
            // If delete operation is successfull then show success message
            $this->Flash->success(
                __('The post with id: %s has been deleted.', h($id))
            );
        } else {
            // If delete operation fails then show error message
            $this->Flash->error(
                __('The post with id: %s could not be deleted.', h($id))
            );
        }
    
        // After completion , redirect to index page i.e. view blogs page
        return $this->redirect(array('action' => 'index'));
    }

    // Authorization function for edit and delete posts
    public function isAuthorized($user) {
        // All registered users can add posts
        if ($this->action === 'add') {
            return true;
        }
    
        // The owner of a post can edit and delete it
        if (in_array($this->action, array('edit', 'delete'))) {
            $postId = (int) $this->request->params['pass'][0];
            if ($this->Post->isOwnedBy($postId, $user['id'])) {
                return true;
            }
        }
    
        // checking if the parent isAuthorized function is return true or false and return the same
        return parent::isAuthorized($user);
    }

}

