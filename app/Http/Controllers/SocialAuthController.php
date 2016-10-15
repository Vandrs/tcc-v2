<?php 

namespace App\Http\Controllers;

use Log;
use Socialite;
use Auth;
use Illuminate\Http\Request;
use App\Utils\Utils;
use App\Models\Enums\EnumSocialLogin;
use App\Models\DB\User;
use App\Models\Business\SocialLoginBusiness;


class SocialAuthController extends Controller
{
	public function fbLogin()
    {
        $scope = ['email', 'public_profile', 'user_work_history', 'user_education_history'];
        return Socialite::driver('facebook')->scopes($scope)->redirect();
    }

    public function fbLoginCallback(Request $request)
    {
        try {
            $fields = ['name', 'email', 'gender', 'work', 'education'];
            $fbUser = Socialite::driver('facebook')->fields($fields)->user();
            $socialLoginBusiness = new SocialLoginBusiness();
            if ($user = $socialLoginBusiness->findUserByIdAndProvider($fbUser->user['id'], EnumSocialLogin::FACEBOOK)) {
                return $this->login($user);
            }  else {
                $data = $socialLoginBusiness->parseFBData($fbUser);
                $request->session()->flash('user', $data);
                return redirect()->route('user.create');
            }
        } catch (\Exception $e) {
            Log::error(Utils::getExceptionFullMessage($e));
            $request->session()->flash('msg','No momento não é possível realizar a conexão com o Facebook.<br/>Tente novamente mais tarde se se o erro persistir entre em contato com o administrador do sistema.');
            $route = route('site.error');
            return redirect()->route('site.error');
        }
    }

    private function login(User $user) 
    {
        Auth::login($user);
        return redirect()->route('admin.home');
    }
}

	