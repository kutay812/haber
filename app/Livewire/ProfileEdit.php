<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileEdit extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $current_password;
    public $profile_image;
    public $current_profile_image;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'password' => ['nullable', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'current_password' => 'required|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB
        ];
    }

    protected $messages = [
        'name.required' => 'Ad Soyad alanı zorunludur.',
        'name.string' => 'Ad Soyad geçerli bir metin olmalıdır.',
        'name.max' => 'Ad Soyad en fazla 255 karakter olabilir.',
        'email.required' => 'E-mail alanı zorunludur.',
        'email.email' => 'Geçerli bir e-mail adresi giriniz.',
        'email.max' => 'E-mail en fazla 255 karakter olabilir.',
        'email.unique' => 'Bu e-mail adresi zaten kullanılıyor.',
        'password.min' => 'Şifre en az 8 karakter olmalıdır.',
        'password.confirmed' => 'Şifre tekrarı eşleşmiyor.',
        'current_password.required' => 'Mevcut şifre alanı zorunludur.',
        'profile_image.image' => 'Profil resmi bir resim dosyası olmalıdır.',
        'profile_image.mimes' => 'Profil resmi jpeg, png, jpg veya gif formatında olmalıdır.',
        'profile_image.max' => 'Profil resmi dosyası en fazla 10MB olabilir.',
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->current_profile_image = $user->profile_image;
    }

    public function updatedProfileImage()
    {
        $this->validateOnly('profile_image');
    }

    public function removeProfileImage()
    {
        $user = Auth::user();

        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
            $user->profile_image = null;
            $user->save();
            $this->current_profile_image = null;
            session()->flash('success', 'Profil resmi kaldırıldı.');
        }
    }

    private function uploadProfileImage()
    {
        if (!$this->profile_image) {
            return null;
        }
        $fileName = 'profile-image/' . Auth::id() . '_' . time() . '.' . $this->profile_image->getClientOriginalExtension();
        $path = $this->profile_image->storeAs('', $fileName, 'public');
        return $path;
    }

    private function deleteOldProfileImage($user)
    {
        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }
    }

    private function resetPasswordFields()
    {
        $this->password = '';
        $this->password_confirmation = '';
        $this->current_password = '';
    }

    public function updateProfile()
    {
        $this->validate();

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Mevcut şifre yanlış.');
            return;
        }

        $emailChanged = $user->email !== $this->email;

        // Profil resmi
        $newProfileImagePath = null;
        if ($this->profile_image) {
            $this->deleteOldProfileImage($user);
            $newProfileImagePath = $this->uploadProfileImage();
        }

        $user->name = $this->name;
        $user->email = $this->email;

        if ($newProfileImagePath) {
            $user->profile_image = $newProfileImagePath;
            $this->current_profile_image = $newProfileImagePath;
        }

        // **Şifre sadece girilmişse güncellensin!**
        if (!empty($this->password)) {
            $user->password = Hash::make($this->password);
        }

        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->resetPasswordFields();
        $this->profile_image = null;

        $this->dispatch('profile-updated');

        $message = 'Profil başarıyla güncellendi.';
        if ($emailChanged) {
            $message .= ' E-mail adresinizi doğrulamanız gerekiyor.';
        }

        session()->flash('success', $message);
    }

    public function getProfileImageUrlProperty()
    {
        if ($this->current_profile_image) {
            return Storage::disk('public')->url($this->current_profile_image);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&size=200&background=6366f1&color=ffffff';
    }

    public function render()
    {
        return view('livewire.profile-edit');
    }
}
