<?php
class SessionController extends ControllerBase
{
    public function indexAction()
    {
        if (!$this->request->isPost()) {
            $this->tag->setDefault('email', 'demo@phalconphp.com');
            $this->tag->setDefault('password', 'phalcon');
        }
    }
    /**
     * Register an authenticated user into session data
     *
     * @param Users $user
     */
    private function _registerSession(Users $user)
    {
        $this->session->set('auth', array(
            'email' => $user->email,
            'ime' => $user->ime
        ));
    }
    /**
     * This action authenticate and logs an user into the application
     *
     */
    public function startAction()
    {
        if ($this->request->isPost()) {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $user = Users::findFirst(array(
                "email = :email:  AND password = :password:",
                'bind' => array('email' => $email, 'password' => $password)
            ));
            if ($user != false) {
                $this->_registerSession($user);
                $this->flash->success('Welcome ' . $user->ime);
                return $this->forward('posts/index');
            }
            $this->flash->error('Wrong email/password');
        }
        return $this->forward('session/index');
    }
    /**
     * Finishes the active session redirecting to the index
     *
     * @return unknown
     */
    public function endAction()
    {
        $this->session->remove('auth');
        $this->flash->success('Goodbye!');
        return $this->forward('index/index');
    }
}