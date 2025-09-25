<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\Debug;
use Fuel\Core\View;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\Session;
use Fuel\Core\Validation;
use Fuel\Core\Uri;
use Fuel\Core\Log;

/**
 * Controller Auth - Xử lý đăng nhập, đăng xuất và xác thực người dùng
 */
class Controller_Auth extends Controller_Template
{
    /**
     * Template cho auth pages
     */
    public $template = 'auth/template';

    /**
     * Hiển thị trang đăng nhập
     */
    public function action_login()
    {
        $data = array();
        
        // Xử lý form đăng nhập khi có POST request
        if (Input::method() == 'POST') {
            // Tạo validation rules
            $val = Validation::forge('login');
            $val->add_field('email', 'Email', 'required|valid_email');
            $val->add_field('password', 'Mật khẩu', 'required|min_length[6]');

            if ($val->run()) {
                $email = Input::post('email');
                $password = Input::post('password');
                $remember_me = Input::post('remember_me') ? true : false;

                if (Auth::instance()->login($email, $password)) {
                    if ($remember_me) {
                        Auth::remember_me();
                    } else {
                        Auth::dont_remember_me();
                    }

                    Response::redirect_back('/');
                } else {
                    Session::set_flash('error', 'Email hoặc mật khẩu không chính xác!');
                    $data['error'] = 'Email hoặc mật khẩu không chính xác!';
                }
            } else {
                $data['errors'] = $val->error();
                Session::set_flash('error', 'Vui lòng kiểm tra lại thông tin đăng nhập!');
            }
        }

        $this->template->title = 'Đăng nhập';
        $this->template->content = View::forge('auth/login', $data);
    }

    /**
     * Đăng xuất
     */
    public function action_logout()
    {
        Auth::logout();

        Session::set_flash('success', 'Đã đăng xuất thành công!');
        Response::redirect(Uri::create('auth/login'));
    }

    public function action_register()
    {
        $data = array();

        if (Input::method() == 'POST') {
            $val = Validation::forge('register');
            $val->add_field('username', 'Họ và tên', 'required|min_length[2]|max_length[50]');
            $val->add_field('email', 'Email', 'required|valid_email');
            $val->add_field('password', 'Mật khẩu', 'required|min_length[6]');
            $val->add_field('confirm_password', 'Xác nhận mật khẩu', 'required|match_field[password]');

            if ($val->run()) {
                $username = Input::post('username');
                $email = Input::post('email');
                $password = Auth::hash_password(Input::post('password'));

                $user = Model_User::forge();
                $user->username = $username;
                $user->email = $email;
                $user->password = $password;
                $user->role_id = 3;
                $user->created_at = date('Y-m-d H:i:s');
                $user->updated_at = date('Y-m-d H:i:s');
                $user->remember_me = false;
                $user->last_login = null;
                $user->login_hash = null;
                $user->save();

                Session::set_flash('success', 'Đăng ký thành công! Vui lòng đăng nhập để tiếp tục!');
                Response::redirect(Uri::create('auth/login'));
            }
            else {
                $data['errors'] = $val->error();
                Session::set_flash('error', 'Vui lòng kiểm tra lại thông tin đăng ký!');
            }
        }

        $this->template->title = 'Đăng ký';
        $this->template->content = View::forge('auth/register', $data);
    }

	/**
	 * Đăng nhập bằng Google - bước 1: redirect đến Google
	 */
	public function action_google()
	{
		try {
			$google = new GoogleService();
			$auth_url = $google->getAuthUrl();
			
			Log::info('Redirecting user to Google OAuth: ' . $auth_url);
			Response::redirect($auth_url);
			
		} catch (Exception $e) {
			Log::error('Google OAuth redirect error: ' . $e->getMessage());
			Session::set_flash('error', 'Có lỗi xảy ra khi kết nối đến Google. Vui lòng thử lại.');
			Response::redirect('login');
		}
	}

	/**
	 * Callback từ Google OAuth - bước 2: xử lý response
	 */
	public function action_google_callback()
	{
		$code = Input::get('code');
		$error = Input::get('error');
		
		// Kiểm tra nếu user từ chối authorization
		if ($error) {
			Log::info('Google OAuth denied by user: ' . $error);
			Session::set_flash('error', 'Đăng nhập Google đã bị hủy.');
			Response::redirect('login');
		}
		
		// Kiểm tra có authorization code không
		if (!$code) {
			Log::error('Google OAuth callback missing authorization code');
			Session::set_flash('error', 'Có lỗi xảy ra trong quá trình đăng nhập Google.');
			Response::redirect('login');
		}
		
		try {
			$google = new GoogleService();
			
			// Authenticate với Google
			if (!$google->authenticate($code)) {
				throw new Exception('Failed to authenticate with Google');
			}
			
			// Lấy thông tin user từ Google
			$google_user = $google->getUserInfo();

			if (!$google_user) {
				throw new Exception('Failed to get user info from Google');
			}
			
			Log::info('Google user info retrieved: ' . $google_user['email']);
			
			// Tìm hoặc tạo user trong database
			$user = $this->findOrCreateGoogleUser($google_user);
			
			if ($user) {
				// Đăng nhập user
				Auth::instance()->force_login($user->id);
				
				// Cập nhật thông tin Google mới nhất
				$this->updateGoogleUserInfo($user, $google_user);
				
				Log::info('User logged in via Google: ' . $user->email);
				Session::set_flash('success', 'Đăng nhập Google thành công! Chào mừng ' . $user->username);
				Response::redirect('/');
			} else {
				throw new Exception('Failed to create or find user');
			}
			
		} catch (Exception $e) {
			Log::error('Google OAuth callback error: ' . $e->getMessage());
			Session::set_flash('error', 'Có lỗi xảy ra trong quá trình đăng nhập Google: ' . $e->getMessage());
			Response::redirect('login');
		}
	}

	/**
	 * Tìm hoặc tạo user từ thông tin Google
	 */
	private function findOrCreateGoogleUser($google_user)
	{
		try {
			// Tìm user theo Google ID trước
			$user = Model_User::query()
				->where('google_id', $google_user['google_id'])
				->get_one();
			
			if ($user) {
				Log::info('Existing Google user found: ' . $user->email);
				return $user;
			}
			
			// Tìm user theo email (có thể đã có account thường)
			$user = Model_User::query()
				->where('email', $google_user['email'])
				->get_one();
			
			if ($user) {
				// Liên kết account hiện tại với Google
				Log::info('Linking existing user with Google: ' . $user->email);
				$user->google_id = $google_user['google_id'];
				$user->is_google_account = 1;
				$user->google_avatar = $google_user['picture'] ?? null;
				$user->save();
				return $user;
			}
			
			// Tạo user mới từ Google
			Log::info('Creating new Google user: ' . $google_user['email']);
			
			$user = Model_User::forge();
			$user->username = $this->generateUsernameFromGoogleName($google_user['name']);
			$user->email = $google_user['email'];
			$user->password = null;
			$user->remember_me = 0; 
			$user->last_login = null;
			$user->login_hash = null;
			$user->role_id = 3; 
			$user->is_google_account = 1;
			$user->created_at = date('Y-m-d H:i:s');
			$user->updated_at = date('Y-m-d H:i:s');
			
			if ($user->save()) {
				Log::info('New Google user created successfully: ' . $user->email);
				return $user;
			} else {
				throw new Exception('Failed to save new Google user');
			}
			
		} catch (Exception $e) {
			Log::error('Error in findOrCreateGoogleUser: ' . $e->getMessage());
			return false;
		}
	}
	
	/**
	 * Cập nhật thông tin Google user
	 */
	private function updateGoogleUserInfo($user, $google_user)
	{
		try {
			$user->google_avatar = $google_user['picture'];
			$user->updated_at = date('Y-m-d H:i:s');
			$user->save();
		} catch (Exception $e) {
			Log::error('Error updating Google user info: ' . $e->getMessage());
		}
	}
	
	private function generateUsernameFromGoogleName($name)
    {
        $username = trim($name);
        $username = str_replace(' ', '.', $username);

        return $username;
    }
}
