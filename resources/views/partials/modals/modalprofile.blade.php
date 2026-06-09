{{-- Name Modal --}}
<div id="nameModal" class="hidden fixed inset-0 backdrop-blur-sm bg-black/20 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8" onclick="event.stopPropagation()">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 font-poppins">Change Name</h3>
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="field" value="name">
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2 font-poppins">Name</label>
                <input type="text" name="name" id="nameInput" value="{{ auth()->user()->name }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#017249] focus:border-transparent font-poppins">
            </div>
            <div class="flex gap-3">
                <button type="button" data-close-modal="name"
                    class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-semibold transition-colors font-poppins cursor-pointer">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 px-6 py-3 bg-[#017249] text-white rounded-xl hover:bg-[#015a3a] font-semibold transition-colors font-poppins cursor-pointer">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Email Modal --}}
<div id="emailModal" class="hidden fixed inset-0 backdrop-blur-sm bg-black/20 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8" onclick="event.stopPropagation()">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 font-poppins">Change Email</h3>
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="field" value="email">
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2 font-poppins">Email</label>
                <input type="email" name="email" id="emailInput" value="{{ auth()->user()->email }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#017249] focus:border-transparent font-poppins">
            </div>
            <div class="flex gap-3">
                <button type="button" data-close-modal="email"
                    class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-semibold transition-colors font-poppins cursor-pointer">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 px-6 py-3 bg-[#017249] text-white rounded-xl hover:bg-[#015a3a] font-semibold transition-colors font-poppins cursor-pointer">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Phone Modal --}}
<div id="phoneModal" class="hidden fixed inset-0 backdrop-blur-sm bg-black/20 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8" onclick="event.stopPropagation()">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 font-poppins">Change Phone Number</h3>
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="field" value="phone">

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2 font-poppins">Phone Number</label>
                <div class="flex gap-3">
                    {{-- Country Code Dropdown --}}
                    <select name="country_code" id="countryCodeSelect"
                        class="w-32 pl-3 pr-8 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#017249] focus:border-transparent font-poppins cursor-pointer bg-white appearance-none bg-[url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e')] bg-[length:1.25rem] bg-[center_right_0.5rem] bg-no-repeat">
                        <option value="+62" {{ auth()->user()->country_code == '+62' ? 'selected' : '' }}>🇮🇩 +62</option>
                        <option value="+1" {{ auth()->user()->country_code == '+1' ? 'selected' : '' }}>🇺🇸 +1</option>
                        <option value="+44" {{ auth()->user()->country_code == '+44' ? 'selected' : '' }}>🇬🇧 +44</option>
                        <option value="+86" {{ auth()->user()->country_code == '+86' ? 'selected' : '' }}>🇨🇳 +86</option>
                        <option value="+81" {{ auth()->user()->country_code == '+81' ? 'selected' : '' }}>🇯🇵 +81</option>
                        <option value="+82" {{ auth()->user()->country_code == '+82' ? 'selected' : '' }}>🇰🇷 +82</option>
                        <option value="+65" {{ auth()->user()->country_code == '+65' ? 'selected' : '' }}>🇸🇬 +65</option>
                        <option value="+60" {{ auth()->user()->country_code == '+60' ? 'selected' : '' }}>🇲🇾 +60</option>
                        <option value="+66" {{ auth()->user()->country_code == '+66' ? 'selected' : '' }}>🇹🇭 +66</option>
                        <option value="+84" {{ auth()->user()->country_code == '+84' ? 'selected' : '' }}>🇻🇳 +84</option>
                        <option value="+63" {{ auth()->user()->country_code == '+63' ? 'selected' : '' }}>🇵🇭 +63</option>
                        <option value="+61" {{ auth()->user()->country_code == '+61' ? 'selected' : '' }}>🇦🇺 +61</option>
                        <option value="+91" {{ auth()->user()->country_code == '+91' ? 'selected' : '' }}>🇮🇳 +91</option>
                    </select>

                    {{-- Phone Input --}}
                    <input type="tel" name="phone" id="phoneInput" value="{{ auth()->user()->phone }}"
                        placeholder="8123456789"
                        class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#017249] focus:border-transparent font-poppins">
                </div>
            </div>

            <div class="flex gap-3">
                <button type="button" data-close-modal="phone"
                    class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-semibold transition-colors font-poppins cursor-pointer">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 px-6 py-3 bg-[#017249] text-white rounded-xl hover:bg-[#015a3a] font-semibold transition-colors font-poppins cursor-pointer">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Password Modal --}}
<div id="passwordModal" class="hidden fixed inset-0 backdrop-blur-sm bg-black/20 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8" onclick="event.stopPropagation()">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 font-poppins">Change Password</h3>
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="field" value="password">
            {{-- Hidden username field for accessibility and password managers --}}
            <input type="email" autocomplete="username" value="{{ auth()->user()->email }}" class="hidden" style="display: none;">
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2 font-poppins">Current Password</label>
                <input type="password" name="current_password" required autocomplete="current-password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#017249] focus:border-transparent font-poppins">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2 font-poppins">New Password</label>
                <input type="password" name="password" required autocomplete="new-password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#017249] focus:border-transparent font-poppins">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2 font-poppins">Confirm New Password</label>
                <input type="password" name="password_confirmation" required autocomplete="new-password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#017249] focus:border-transparent font-poppins">
            </div>
            <div class="flex gap-3">
                <button type="button" data-close-modal="password"
                    class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-semibold transition-colors font-poppins cursor-pointer">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 px-6 py-3 bg-[#017249] text-white rounded-xl hover:bg-[#015a3a] font-semibold transition-colors font-poppins cursor-pointer">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
