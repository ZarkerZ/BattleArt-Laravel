<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/doro.ico') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #a78bfa 0%, #d8b4fe 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 1.5rem 1rem;
        }

        .main-container {
            width: 100%;
            max-width: 800px;
            background-color: white;
            border-radius: 1.5rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .banner {
            height: 180px;
            background-image: url('{{ $user->user_banner_pic ? asset("assets/uploads/" . $user->user_banner_pic) : asset("assets/images/night-road.png") }}');
            background-size: cover;
            background-position: center;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.875rem;
            font-weight: 800;
            padding: 1rem;
        }

        .profile-img-container {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #333;
            border: 4px solid white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            flex-shrink: 0;
        }

        .toggle-label input:checked+.slider {
            background-color: #8b5cf6;
        }

        .slider {
            background-color: #e0e7ff;
            transition: .4s;
        }

        .toggle-label .slider+span {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>

<body>
    <a href="{{ route('admin.profile') }}" class="absolute top-4 left-4 bg-white bg-opacity-30 backdrop-blur-sm text-white text-sm font-semibold py-2 px-4 rounded-xl shadow-md hover:bg-opacity-40 transition duration-200 flex items-center z-10">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        Back to Admin Profile
    </a>

    <div id="cropperModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center p-4 hidden z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 space-y-4">
            <h3 class="text-xl font-bold text-gray-800">Reposition and Crop Image</h3>
            <div class="w-full h-64 bg-gray-100"><img id="imageToCrop" src=""></div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeProfileModal()" class="px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">Cancel</button>
                <button type="button" id="cropButton" class="px-4 py-2 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition">Crop & Apply</button>
            </div>
        </div>
    </div>

    <div id="bannerCropperModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center p-4 hidden z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl p-6 space-y-4">
            <h3 class="text-xl font-bold text-gray-800">Reposition and Crop Banner</h3>
            <div class="w-full h-80 bg-gray-100"><img id="bannerToCrop" src=""></div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeBannerModal()" class="px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">Cancel</button>
                <button type="button" id="cropBannerButton" class="px-4 py-2 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition">Crop & Apply Banner</button>
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="banner">
            <label for="bannerUpload" class="absolute top-4 right-4 bg-white bg-opacity-30 backdrop-blur-sm text-white text-sm font-semibold py-2 px-3 rounded-xl shadow-md hover:bg-opacity-40 transition duration-200 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.218A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.218A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Change Banner
            </label>
            <input type="file" id="bannerUpload" class="hidden" accept="image/*">
        </div>

        <form id="profileSettingsForm" action="{{ route('admin.update') }}" method="post" enctype="multipart/form-data" class="px-8 pt-6 pb-8 space-y-8">
            @csrf
            <input type="hidden" name="croppedImage" id="croppedImage">
            <input type="hidden" name="croppedBanner" id="croppedBanner">

            <div class="flex flex-col md:flex-row items-start md:items-center space-y-6 md:space-y-0 md:space-x-6">
                <div class="flex flex-col items-center w-full md:w-auto">
                    <div id="profileImage" class="profile-img-container mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#ddd" class="w-full h-full p-4 {{ $user->user_profile_pic ? 'hidden' : '' }}" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                        </svg>
                        <img id="editImagePreview"
                             src="{{ $user->user_profile_pic ? asset('assets/uploads/' . $user->user_profile_pic) : asset('assets/images/blank-profile-picture.png') }}"
                             class="w-full h-full object-cover {{ !$user->user_profile_pic ? 'hidden' : '' }}"
                             alt="Profile Avatar"
                             onerror="this.onerror=null; this.src='{{ asset('assets/images/blank-profile-picture.png') }}';">
                    </div>
                    <label for="profileUpload" class="text-sm font-bold py-2 px-4 text-purple-700 bg-purple-100 rounded-xl shadow-md hover:bg-purple-200 transition duration-150 cursor-pointer whitespace-nowrap">
                        Upload Profile Image
                        <input type="file" id="profileUpload" class="hidden" accept="image/*">
                    </label>
                </div>
                <div class="flex-grow w-full md:w-auto mt-4 md:mt-0">
                    <div class="grid grid-cols-1 gap-4 items-end">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Username</label>
                            <input type="text" id="fullName" name="fullName" value="{{ old('fullName', $user->user_userName) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Email</label>
                            <input type="email" name="user_email" value="{{ old('user_email', $user->user_email) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500">
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label for="userBio" class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                <textarea id="userBio" name="userBio" rows="4" oninput="updateCharCount()" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500">{{ old('userBio', $user->user_bio) }}</textarea>
                <div class="text-right text-xs text-gray-500 mt-1"><span id="charCount">0</span> / 500 characters</div>
            </div>

            <hr class="border-gray-200" />

            <div>
                <h2 class="text-xl font-bold text-gray-700 mb-4">Display Settings</h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-1">
                        <p class="text-gray-700 text-base">Show "Your Art" Tab</p>
                        <label class="toggle-label relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="toggleArt" name="toggleArt" class="sr-only peer" {{ $user->show_art ? 'checked' : '' }}>
                            <span class="slider w-11 h-6 rounded-full"></span><span class="absolute left-[2px] top-[2px] w-5 h-5 rounded-full transition-all peer-checked:translate-x-5"></span>
                        </label>
                    </div>
                    <div class="flex justify-between items-center py-1">
                        <p class="text-gray-700 text-base">Show "History" Tab</p>
                        <label class="toggle-label relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="toggleHistory" name="toggleHistory" class="sr-only peer" {{ $user->show_history ? 'checked' : '' }}>
                            <span class="slider w-11 h-6 rounded-full"></span><span class="absolute left-[2px] top-[2px] w-5 h-5 rounded-full transition-all peer-checked:translate-x-5"></span>
                        </label>
                    </div>
                    <div class="flex justify-between items-center py-1">
                        <p class="text-gray-700 text-base">Show "Comments" Tab</p>
                        <label class="toggle-label relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="toggleComments" name="toggleComments" class="sr-only peer" {{ $user->show_comments ? 'checked' : '' }}>
                            <span class="slider w-11 h-6 rounded-full"></span><span class="absolute left-[2px] top-[2px] w-5 h-5 rounded-full transition-all peer-checked:translate-x-5"></span>
                        </label>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class='p-3 mb-4 rounded-lg text-sm font-medium bg-green-100 text-green-800'>{{ session('success') }}</div>
            @endif

            <div class="pt-4 flex justify-center space-x-4 border-t border-gray-100">
                <button id="saveButton" type="submit" class="flex items-center px-6 py-2 bg-purple-600 text-white font-semibold rounded-xl shadow-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition">
                    Save Changes
                </button>
                <a href="{{ route('admin.profile') }}" class="flex items-center px-6 py-2 bg-gray-600 text-white font-semibold rounded-xl shadow-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        function updateCharCount() {
            const bioText = document.getElementById('userBio').value;
            const countElement = document.getElementById('charCount');
            countElement.textContent = bioText.length;
        }

        // Cropper Logic (Identical to user profile)
        const profileModal = document.getElementById('cropperModal');
        const profileImageToCrop = document.getElementById('imageToCrop');
        const profileCropButton = document.getElementById('cropButton');
        const profileFileInput = document.getElementById('profileUpload');
        let profileCropper;

        profileFileInput.addEventListener('change', (event) => {
            handleFileSelect(event, profileImageToCrop, profileModal, (cropperInstance) => {
                profileCropper = new Cropper(cropperInstance, { aspectRatio: 1, viewMode: 1, dragMode: 'move', background: false, autoCropArea: 0.8 });
            });
        });

        profileCropButton.addEventListener('click', () => {
            if (!profileCropper) return;
            const canvas = profileCropper.getCroppedCanvas({ width: 250, height: 250 });
            document.getElementById('editImagePreview').src = canvas.toDataURL('image/png');
            document.querySelector('.profile-img-container svg')?.classList.add('hidden');
            document.getElementById('editImagePreview').classList.remove('hidden');
            document.getElementById('croppedImage').value = canvas.toDataURL('image/png');
            closeModal(profileModal, profileCropper, profileFileInput);
            profileCropper = null;
        });

        const bannerModal = document.getElementById('bannerCropperModal');
        const bannerImageToCrop = document.getElementById('bannerToCrop');
        const bannerCropButton = document.getElementById('cropBannerButton');
        const bannerFileInput = document.getElementById('bannerUpload');
        let bannerCropper;

        bannerFileInput.addEventListener('change', (event) => {
            handleFileSelect(event, bannerImageToCrop, bannerModal, (cropperInstance) => {
                bannerCropper = new Cropper(cropperInstance, { aspectRatio: 16 / 9, viewMode: 1, dragMode: 'move', background: false, autoCropArea: 1 });
            });
        });

        bannerCropButton.addEventListener('click', () => {
            if (!bannerCropper) return;
            const canvas = bannerCropper.getCroppedCanvas({ width: 800, height: 450 });
            document.querySelector('.banner').style.backgroundImage = `url('${canvas.toDataURL('image/png')}')`;
            document.getElementById('croppedBanner').value = canvas.toDataURL('image/png');
            closeModal(bannerModal, bannerCropper, bannerFileInput);
            bannerCropper = null;
        });

        function handleFileSelect(event, imgElement, modalElement, callback) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                imgElement.src = e.target.result;
                modalElement.classList.remove('hidden');
                callback(imgElement);
            };
            reader.readAsDataURL(file);
        }

        function closeModal(modal, cropper, input) {
            if (cropper) cropper.destroy();
            modal.classList.add('hidden');
            if (input) input.value = '';
        }

        window.closeProfileModal = () => closeModal(profileModal, profileCropper, profileFileInput);
        window.closeBannerModal = () => closeModal(bannerModal, bannerCropper, bannerFileInput);
        document.addEventListener('DOMContentLoaded', updateCharCount);
    </script>
</body>
</html>
