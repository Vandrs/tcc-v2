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
        if (Auth::check()) {
            return redirect()->route('admin.home');
        }
        $scope = ['email', 'public_profile', 'user_work_history', 'user_education_history'];
        return Socialite::driver('facebook')->scopes($scope)->redirect();
    }

    public function gpLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.home');
        }
        $scope = ['email', 'profile',];
        return Socialite::driver('google')->scopes($scope)->redirect();
    }

    public function linkedinLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.home');
        }   
        return Socialite::driver('linkedin')->redirect();
    }

    public function fbLoginCallback(Request $request)
    {
        try {
            $fields = ['name', 'email', 'gender', 'work', 'education'];
            $fbUser = Socialite::driver('facebook')->fields($fields)->user();
            $socialLoginBusiness = new SocialLoginBusiness();
            if ($user = $socialLoginBusiness->findUserByIdAndProvider($fbUser->user['id'], EnumSocialLogin::FACEBOOK)) {
                $user->update(['photo' => $fbUser->getAvatar()]);
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

    public function gpLoginCallback(Request $request)
    {
        try {
            $gpUser = Socialite::driver('google')->user();
            $socialLoginBusiness = new SocialLoginBusiness();
            if ($user = $socialLoginBusiness->findUserByIdAndProvider($gpUser->id, EnumSocialLogin::GOOGLE_PLUS)) {
                $user->update(['photo' => $gpUser->getAvatar()]);
                return $this->login($user);
            }  else {
                $data = $socialLoginBusiness->parseGPData($gpUser);
                $request->session()->flash('user', $data);
                return redirect()->route('user.create');
            }
        } catch (\Exception $e) {
            Log::error(Utils::getExceptionFullMessage($e));
            $request->session()->flash('msg','No momento não é possível realizar a conexão com o Google+.<br/>Tente novamente mais tarde se se o erro persistir entre em contato com o administrador do sistema.');
            $route = route('site.error');
            return redirect()->route('site.error');
        }
    }

    public function linkedinLoginCallback(Request $request)
    {
        try {
            $linkedinUser = Socialite::driver('linkedin')->user();
            $socialLoginBusiness = new SocialLoginBusiness();
            if ($user = $socialLoginBusiness->findUserByIdAndProvider($linkedinUser->id, EnumSocialLogin::LINKEDIN)) {
                $user->update(['photo' => $linkedinUser->getAvatar()]);
                return $this->login($user);
            } else {
                $data = $socialLoginBusiness->parseLinkedinData($linkedinUser);
                $request->session()->flash('user', $data);
                return redirect()->route('user.create');
            }
        } catch (\Exception $e) {
            Log::error(Utils::getExceptionFullMessage($e));
            $request->session()->flash('msg','No momento não é possível realizar a conexão com o LinkedIn.<br/>Tente novamente mais tarde se se o erro persistir entre em contato com o administrador do sistema.');
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

	