<?php
class HomeController extends AppController
{
	public $uses = array('User', 'Message');

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('main', 'thankyou', 'chatlist_api', 'load_messages', 'send_message', 'remove_message');
	}

	public function thankyou()
	{
		$this->set('message', 'Welcome to our application! We are glad that you registered to our application.');
		$this->set('page', 'Thank You');
		$this->layout = 'guest';
	}

	public function index()
	{
		echo "die";
		die();
	}

	public function main()
	{
		$this->set('page', 'Messages');
		$this->layout = 'user';
	}

	public function chatlist_api()
	{

		if ($this->request->is('get')) {

			$userId = $this->Auth->user('id');
			$searchTerm = isset($this->request->query['search']) ? $this->request->query['search'] : '';
            $limit = $this->request->query('limit') ?? 10;

			$conditions = [];
			if (!empty($searchTerm)) {
				$conditions[] = [
					'OR' => [
						'User.name LIKE' => '%' . $searchTerm . '%',
						'User.email LIKE' => '%' . $searchTerm . '%',
					],
				];
			}

			$conditions[] = ['User.id !=' => $userId];

			$totalUsers = $this->User->find('count', [
				'conditions' => $conditions
			]);

			$users = $this->User->find('all', [
				'fields' => [
					'User.id',
					'User.name',
					'User.email',
					'User.profile_url',
					'LatestMessage.message',
					'LatestMessage.created_at'
				],
				'joins' => [
					[
						'table' => 'messages',
						'alias' => 'LatestMessage',
						'type' => 'LEFT',
						'conditions' => [
							'LatestMessage.sender_id = User.id', // Only get messages sent by the user
							'LatestMessage.recipient_id = ' . $userId, // Only consider messages sent to the logged-in user
							'LatestMessage.created_at = (
                    SELECT MAX(m.created_at)
                    FROM messages m
                    WHERE m.sender_id = User.id AND m.recipient_id = ' . $userId . ')'
						]
					]
				],
				'conditions' => $conditions,
				'group' => [
					'User.id',
					'User.name',
					'User.email',
					'User.profile_url',
					'LatestMessage.message',
					'LatestMessage.created_at'
				],
				'limit' => $limit
			]);

         
			echo json_encode(['users' => $users, 'totalUsers' => $totalUsers, 'limit' => $limit]);

			$this->autoRender = false;
			return;
		} else {
			echo json_encode(['error' => 'Invalid Request']);
			exit;
		}
	}

	public function load_messages()
	{
		if ($this->request->is('ajax')) {

			$loggedInUserId = $this->Auth->user('id');
			$selectedUserId = $this->request->data['id'];
			$searchTerm = isset($this->request->data['searchTerm']) ? $this->request->data['searchTerm'] : '';

			$page = isset($this->request->data['page']) ? (int)$this->request->data['page'] : 1;
			$messagesPerPage = 10;
            $limit = $page * $messagesPerPage;


			// Fetch messages between the logged-in user and the selected user
			$query = $this->Message->find('all', [
				'conditions' => [
					'OR' => [
						['Message.sender_id' => $loggedInUserId, 'Message.recipient_id' => $selectedUserId],
						['Message.sender_id' => $selectedUserId, 'Message.recipient_id' => $loggedInUserId],
					],
					'Message.message LIKE' => '%' . $searchTerm . '%',
				],
				'order' => ['Message.created_at' => 'ASC'],
				'limit' => $limit
			]);

			$totalMessages = $this->Message->find('count', [
				'conditions' => [
					'OR' => [
						['Message.sender_id' => $loggedInUserId, 'Message.recipient_id' => $selectedUserId],
						['Message.sender_id' => $selectedUserId, 'Message.recipient_id' => $loggedInUserId],
					],
					'Message.message LIKE' => '%' . $searchTerm . '%',
				],
			]);

			$result = [];
			$previousDate = null;

			foreach ($query as $message) {
				$messageDate = date("Y-m-d", strtotime($message['Message']['created_at']));

				// Check if the message date is different from the previous message date
				if ($messageDate !== $previousDate) {
					$result[] = [
						'divider' => date("F j, Y", strtotime($messageDate)),
					];
					$previousDate = $messageDate;
				}

				$result[] = [
					'class' => ($message['Message']['sender_id'] == $loggedInUserId) ? 'sender' : 'reply',
					'msgId' => $message['Message']['id'],
					'content' => htmlspecialchars($message['Message']['message']),
					'time' => date("h:i a", strtotime($message['Message']['created_at'])),
					'showEdit' => $message['Message']['sender_id'] == $loggedInUserId,
					'showDelete' => $message['Message']['sender_id'] == $loggedInUserId,
				];
			}

			echo json_encode(['result' => $result, 'totalMessages' => $totalMessages]);
			exit; // Prevent any further output
		} else {
			echo json_encode(['error' => 'Invalid Request']);
			exit;
		}
	}

	public function send_message()
	{
		if ($this->request->is('ajax')) {

			$data = [
				'recipient_id' => $this->request->data['recipient_id'],
				'message' => $this->request->data['message'],
				'sender_id' => $this->Auth->user('id')
			];

			if (empty($this->request->data['messageId'])) {

				if ($this->Message->save($data)) {
					$response = ['status' => 'success', 'message' => 'Message sent successfully.'];
				} else {
					$response = ['status' => 'error', 'message' => 'Failed to send message.'];
				}
			} else {

				$messageId = $this->request->data['messageId'];

				$this->Message->id = $messageId;
				if ($this->Message->exists()) {

					 $existingMessage = $this->Message->findById($messageId);
                
					 $updatedData = [
						 'message' => $data['message'],
						 'updated_at' => date('Y-m-d H:i:s'),
						 'created_at' => $existingMessage['Message']['created_at']
					 ];

					if ($this->Message->save($updatedData)) { 
						$response = ['status' => 'success', 'message' => 'Message updated successfully.'];
					} else {
						$response = ['status' => 'error', 'message' => 'Failed to update message.'];
					}
				} else {
					$response = ['status' => 'error', 'message' => 'Message not found.'];
				}
			}

			echo json_encode(['response' => $response]);
			exit;
		} else {
			echo json_encode(['error' => 'Invalid Request']);
			exit;
		}
	}

	public function remove_message()
	{
		if ($this->request->is('ajax')) {
			if (!empty($this->request->data['messageId'])) {
				$messageId = $this->request->data['messageId'];

				if ($this->Message->delete($messageId)) {
					$response = ['status' => 'success', 'message' => 'Message deleted successfully.'];
				} else {
					$response = ['status' => 'error', 'message' => 'Failed to delete message.'];
				}
			} else {
				$response = ['status' => 'error', 'message' => 'Message ID not found.'];
			}

			echo json_encode(['response' => $response]);
			exit;
		} else {
			echo json_encode(['error' => 'Invalid Request']);
			exit;
		}
	}
}
