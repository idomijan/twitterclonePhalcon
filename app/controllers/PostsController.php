<?php
use Phalcon\Mvc\Controller;

class PostsController extends Controller
{
    public function indexAction()
    {

    }

    public function createAction()
    {
        $post = new Posts();

        $success = $post->save($this->request->getPost(), array('id_posta', 'poruka', 'created_at'));

        if ($success) {
            echo "Your message has been posted !";
        } else {
            echo "Sorry, the following problems were generated: ";
            foreach ($post->getMessages() as $message) {
                echo $message->getMessage(), "<br/>";
            }
        }

        $this->view->disable();
    }

    public function deleteAction()
    {
    }
}