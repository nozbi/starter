<?php

namespace App;

use Nozbi\Uikit\App\App as BaseApp;
use Nozbi\Uikit\App\Config;
use Nozbi\Uikit\App\Translations;
use Nozbi\Uikit\App\Colors;
use Override;

class App extends BaseApp
{
    private function isAuthenticated(): bool
    {
        return auth()->check();
    }

    #[Override]
    protected function usesLivewireNavigate(): bool
    {
        return true;
    }

    #[Override]
    protected function isSecured(): bool
    {
        return false;
    }

    #[Override]
    protected function getLogo(): ?string
    {
        return '/images/logo.png';
    }

    #[Override]
    protected function getAvatar(): ?string
    {
        if (!$this->isAuthenticated()) 
        {
            return '/images/avatar.png';
        }
        if (auth()->user()->profile_photo_path === null)
        {
            $name = trim(collect(explode(' ', auth()->user()->name))->map(function ($segment) {
                return mb_substr($segment, 0, 1);
            })->join(' '));
            return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=7F9CF5&background=EBF4FF';
        }
        return '/storage/' . auth()->user()->profile_photo_path;
    }

    #[Override]
    protected function getOuterWrapper(): ?string
    {
        return 'uikit.outer-wrapper';
    }

    #[Override]
    protected function getInnerWrapper(): ?string
    {
        return null;
    }

    #[Override]
    protected function getTranslations(): Translations
    {
        return new Translations('open side bar', 'close side bar', 'Search...', 'logo', 'account', 'Powered by Laravel Jetstream');
    }

    #[Override]
    protected function getColors(): Colors
    {
        return new Colors('#1F2937', '#F3F4F6', '#818CF8');
    }

    #[Override]
    protected function getRootRoute(): string
    {
        if ($this->isAuthenticated())
        {
            return 'dashboard';
        }
        return 'home';
    }

    #[Override]
    protected function getConfig(): Config
    {
        return new Config()
            ->menuRoute('home', 'Home')
            ->menuRoute('dashboard', 'Dashboard', null, false)
            ->startMenu('auth', 'Account')
                ->startMenuRoute('two-factor', 'Login', null, false)
                    ->route('login', 'Authenticator', false)
                ->endMenuRoute()
                ->startMenuRoute('password', 'Login', null, false)
                    ->route('request', 'Forgot password', false)
                    ->route('reset', 'Reset password', false)
                ->endMenuRoute()
                ->menuRoute('login', 'Login', 0, false)
                ->menuRoute('register', 'Register', 1, false)
            ->endMenu()
            ->startMenu('account', 'Account')
                ->startMenuRoute('profile', 'Profile', 0)
                    ->route('show', 'Settings', false)
                ->endMenuRoute()
                ->menuRoute('logout-redirect', 'Logout', 1)
            ->endMenu()
        ;
    }

    protected function can_twoFactor(): bool
    {
        return request()->routeIs('two-factor.login');
    }

    protected function can_password(): bool
    {
        return request()->routeIs('password.request', 'password.reset');
    }

    protected function can_login(): bool
    {
        return !request()->routeIs('two-factor.login', 'password.request', 'password.reset');
    }

    protected function can_home(): bool
    {
        return !$this->isAuthenticated();
    }

    protected function can_auth(): bool
    {
        return !$this->isAuthenticated();
    }

    protected function can_dashboard(): bool
    {
        return $this->isAuthenticated();
    }

    protected function can_account(): bool
    {
        return $this->isAuthenticated();
    }
}