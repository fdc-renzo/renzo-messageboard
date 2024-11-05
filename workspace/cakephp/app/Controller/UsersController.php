<?php
class UsersController extends AppController
{
    public $uses = ['User'];
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(['login', 'register', 'profile']); // CSRF always restrict your whitelists to a per-controller basis
    }

    public function login()
    {
        $this->set('title', 'Message Board');

        if ($this->request->is('ajax')) {
            $this->loadModel('User'); // Ensure the User model is loaded

            // Load the password hasher
            $passwordHasher = new BlowfishPasswordHasher();

            // Find the user by email
            $email = $this->request->data['email'];
            $password = $this->request->data['password'];

            $errors = [];

            if (empty($email)) {
                $errors['email'] = __('Email is required.');
            }

            if (empty($password)) {
                $errors['password'] = __('Password is required.');
            }

            if (!empty($errors)) {
                echo json_encode([
                    'status' => 'error',
                    'errors' => $errors // Pass the validation errors
                ]);
                $this->autoRender = false;
                return;
            }

            $user = $this->User->find('first', array(
                'conditions' => array('User.email' => $email)
            ));

            if ($user && $passwordHasher->check($password, $user['User']['password'])) {
                // Log the user in
                $this->Auth->login($user['User']);

                if (!$this->User->updateLastLoginTime($user['User']['id'])) {
                    $this->set([
                        'status' => 'error',
                        'message' => __('Could not update last login time. Please try again.')
                    ]);
                    $this->set('_serialize', ['status', 'message']);
                    return;
                }

                echo json_encode([
                    'status' => 'success',
                    'user' => $this->Auth->user()
                ]);

                exit;
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => __('Invalid email or password.')
                ]);
            }

            $this->autoRender = false;
            return;
        }

        $this->layout = 'guest';
    }

    public function register()
    {
        $this->set('title', 'Message Board');

        if ($this->request->is('ajax')) {
            $this->User->create();

            if ($this->User->save($this->request->data)) {

                $email = $this->request->data['email'];

                $user = $this->User->find('first', array(
                    'conditions' => array('User.email' => $email)
                ));

                $this->Auth->login($user['User']);

                if (!$this->User->updateLastLoginTime($user['User']['id'])) {
                    $this->set([
                        'status' => 'error',
                        'message' => __('Could not update last login time. Please try again.')
                    ]);
                    $this->set('_serialize', ['status', 'message']);
                    return;
                }

                echo json_encode([
                    'status' => 'success',
                    'message' => __('The user has been registered.'),
                    'user' => $this->Auth->user()
                ]);
                exit;
            } else {
                // Validation error response
                $errors = $this->User->validationErrors;
                echo json_encode([
                    'status' => 'error',
                    'message' => __('The user could not be registered. Please, try again.'),
                    'errors' => $errors
                ]);
            }
            $this->autoRender = false;
            return; // Exit method
        }

        $this->layout = 'guest';
    }

    public function profile()
    {
        $this->set('page', 'Profile');

        $this->User->id = $this->Auth->user('id');
        $currentUser = $this->User->findById($this->User->id);

        if ($this->request->is('ajax')) {

            $data = $this->request->data;

            //CakeLog::write('debug', 'Profile Data: ' . print_r($data, true));

            $previousImage = $currentUser['User']['profile'] ?? null;

            $errors = [];

            if ($previousImage == null) {
                if (empty($_FILES['profile']['tmp_name'])) {
                    $errors['profile'] = __('Profile image is required. Acceptable formats: jpg, png, gif.');
                } elseif (!in_array($_FILES['profile']['type'], ['image/jpeg', 'image/png', 'image/gif', 'image/avif'])) {
                    $errors['profile'] = __('Please upload a valid image format (jpg, png, gif).');
                }
            }

            if (empty($data['name'])) {
                $errors['name'] = __('A name is required.');
            } elseif (strlen($data['name']) < 5 || strlen($data['name']) > 20) {
                $errors['name'] = __('Name must be between 5 and 20 characters long.');
            }

            if (empty($data['birthdate'])) {
                $errors['birthdate'] = __('A birthdate is required.');
            }

            if (empty($data['gender'])) {
                $errors['gender'] = __('Gender is required.');
            }

            if (empty($data['hubby'])) {
                $errors['hubby'] = __('A Hubby is required.');
            }

            if (!empty($errors)) {
                echo json_encode([
                    'status' => 'error',
                    'errors' => $errors
                ]);
                $this->autoRender = false;
                return;
            }

            if (isset($_FILES['profile']) && !empty($_FILES['profile']['tmp_name'])) {
                // Define the upload path
                $uploadPath = WWW_ROOT . 'assets/uploads/';
                $imagePath = $uploadPath . basename($_FILES['profile']['name']);

                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                if (move_uploaded_file($_FILES['profile']['tmp_name'], $imagePath)) {
                    // unlink
                    if ($previousImage && file_exists(WWW_ROOT . $previousImage)) {
                        unlink(WWW_ROOT . $previousImage);
                    }

                    $data['profile'] = 'assets/uploads/' . basename($_FILES['profile']['name']);
                    $data['profile_url'] = Router::url('/' . $data['profile'], true);
                } else {
                    echo json_encode(['status' => 'error', 'errors' => ['profile' => 'Failed to move uploaded file.']]);
                    $this->autoRender = false;
                    return;
                }
            }

            // Update user data
            if ($this->User->save($data)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to save data']);
            }


            $this->autoRender = false;
            return;
        }

        $this->set('userdata', $currentUser);

        $this->layout = 'user';
    }

    public function profileView()
    {
        $this->set('page', 'Profile View');

        $userId = $this->Auth->user('id');
        $currentUser = $this->User->findById($userId);

        if (!$currentUser) {
            echo json_encode(['error' => 'User not found.']);
        }

        $birthdate = $currentUser['User']['birthdate'];
        $age = null;

        if (!empty($birthdate)) {
            $age = $this->getAge($birthdate);
            $this->set('age', $age);
        }

        $this->set('userdata', $currentUser);

        $this->layout = 'user';
    }


    public function changeEmail()
    {

        if ($this->request->is('ajax')) {

            $user = $this->Auth->user();
            if ($user) {
                $email = $this->request->data('email');

                $errors = [];

                if (empty($email)) {
                    $errors['email'] = __('Email is required.');
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($email)) {
                    $errors['email'] = __('Invalid email format.');
                }

                if ($this->User->find('count', [
                    'conditions' => [
                        'User.email' => $email,
                        'User.id !=' => $user['id']
                    ]
                ]) > 0) {
                    $errors['email'] = __('Email is already in use.');
                }

                if (!empty($errors)) {
                    echo json_encode([
                        'status' => 'error',
                        'errors' => $errors
                    ]);
                    $this->autoRender = false;
                    return;
                }

                $this->User->id = $this->Auth->user('id');
                if ($this->User->save(['email' => $email])) {
                    echo json_encode(['status' => 'success', 'email' => $email ]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Unable to update email.']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'User not authenticated.']);
            }

            $this->autoRender = false;
            return;
        }

        echo json_encode([
            'status' => 'error',
            'message' => __('Invalid Request.')
        ]);
    }

    public function changePassword()
    {
        if ($this->request->is('ajax')) {
            $user = $this->Auth->user();
            if ($user) {
                $this->loadModel('User');

                $currentUser = $this->User->findById($user['id']);

                if (!$currentUser) {
                    echo json_encode(['status' => 'error', 'message' => __('User not found.')]);
                    $this->autoRender = false;
                    return;
                }

                $passwordHasher = new BlowfishPasswordHasher();
                $old_password = $this->request->data('old_password');
                $new_password = $this->request->data('new_password');
                $errors = [];

                if (empty($old_password)) {
                    $errors['old_password'] = __('Old password is required.');
                } elseif (!$passwordHasher->check($old_password, $currentUser['User']['password'])) {
                    $errors['old_password'] = __('Old password is incorrect.');
                }

                if (empty($new_password)) {
                    $errors['new_password'] = __('New password is required.');
                }

                if (!empty($errors)) {
                    echo json_encode([
                        'status' => 'error',
                        'errors' => $errors
                    ]);
                    $this->autoRender = false;
                    return;
                }

                $this->User->id = $user['id'];
                if ($this->User->save(['password' => $new_password])) {
                    echo json_encode(['status' => 'success']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => __('Unable to update password.')]);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => __('User not authenticated.')]);
            }

            $this->autoRender = false;
            return;
        }

        echo json_encode([
            'status' => 'error',
            'message' => __('Invalid Request.')
        ]);
    }

    private function getAge($birthdate)
    {
        $birthDate = new DateTime($birthdate);
        $today = new DateTime();
        $age = $today->diff($birthDate)->y;
        return $age;
    }

    public function logout()
    {
        $this->Auth->logout();
        $this->Flash->success(__('You have been logged out.'));
        return $this->redirect(['action' => 'login']);
    }
}
