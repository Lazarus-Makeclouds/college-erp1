<?php
require_once "./header.php";
require_once "./core/init.php";
$user = new User();
if ($user->isLoggedIn()) {
    Redirect::to('./dashboard.php?src=index');
}
if (Input::exists("post")) {
    $validate = new Validate();
    $validation = $validate->check($_POST, [
        'username' => [
            "required" => true
        ],
        'password' => [
            "required" => true
        ]
    ]);
    if ($validation->passed()) {
        $user = new User();
        $login = $user->login(Input::get('username'), Input::get('password'));
        if ($login) {
            if ($user->hasPermission("admin")) {
                Redirect::to('./dashboard.php?src=index');
            } else {
                $wrong_login = "You dont have permission to access this page";
                $user->logout();
            }
        } else {
            $error = "Wrong Credentials";
        }
    }
}
?>



<div class="flex flex-col justify-center items-center min-h-screen bg-gradient-to-br from-gray-50 via-gray-200 to-gray-300">
    <div id="adminlogin"  class="w-full max-w-lg px-4">
        <form 
            action="" 
            method="post" 
            class="bg-white rounded-lg shadow-lg p-8 "
        >
            <div class="text-center mb-8">
                <i class="fa-solid fa-user-tie text-6xl text-orange-500 mb-4 transition-transform duration-300 ease-in-out hover:rotate-12"></i>
                <h2 class="text-3xl font-bold text-gray-800">Admin Login</h2>
            </div>
            <div class="mb-6">
                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    required
                    class="w-full p-3 rounded-md border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition duration-200 ease-in-out"
                />
            </div>
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    class="w-full p-3 rounded-md border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition duration-200 ease-in-out"
                />
            </div>
            <div class="flex justify-center">
                <button
                    type="submit"
                    class="px-6 py-3 w-full text-white font-medium bg-gradient-to-r from-blue-500 to-blue-600 rounded-md hover:from-blue-600 hover:to-blue-700 transition-transform duration-300 ease-in-out transform hover:scale-105 shadow-lg"
                >
                    Login
                </button>
            </div>
            <?php if(isset($error)){ ?>
                <p class="text-center text-red-500 mt-4"><?php echo $error; ?></p>
            <?php unset($error); } ?>
            <?php if(isset($wrong_login)){ ?>
                <p class="text-center text-red-500 mt-4"><?php echo $wrong_login; ?></p>
            <?php unset($wrong_login); } ?>
        </form>
    </div>
</div>




</body>
</html>