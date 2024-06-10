<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Login | Workforce Management Common Resources</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<link href="/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
	</head>
	<body id="kt_body" class="bg-body">
		<div class="d-flex flex-column flex-root">
			<div class="d-flex flex-column flex-lg-row flex-column-fluid">
				<div class="d-flex flex-column flex-lg-row-auto w-xl-600px positon-xl-relative" style="background-color: #F2C98A">
					<div class="d-flex flex-column position-xl-fixed top-0 bottom-0 w-xl-600px scroll-y">
						<div class="d-flex flex-row-fluid flex-column text-center p-10 pt-lg-20">
							<a href="#" class="py-9 mb-5">
								<img alt="Logo" src="assets/media/logos/logo-2.svg" class="h-60px" />
							</a>
							<h1 class="fw-bolder fs-2qx pb-5 pb-md-10" style="color: #986923;">Workforce Management Common Resources</h1>
							<p class="fw-bold fs-2" style="color: #986923;">Discover Amazing Metronic
							<br />with great build tools</p>
						</div>
						<div class="d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-100px min-h-lg-350px" style="background-image: url(/assets/media/illustrations/sigma-1/13.png"></div>
					</div>
				</div>
				<div class="d-flex flex-column flex-lg-row-fluid py-10">
					<div class="d-flex flex-center flex-column flex-column-fluid">
						<div class="w-lg-500px p-10 p-lg-15 mx-auto">
							<form class="form w-100" id="kt_sign_in_form" method="POST">
								{{ csrf_field() }}
								<div class="text-center mb-10">
									<h1 class="text-dark mb-3">Sign In</h1>
								</div>
								<div class="fv-row mb-10">
									<label class="form-label fs-6 fw-bolder text-dark">NIK</label>
									<input class="form-control form-control-lg form-control-solid text-center" placeholder="Masukan NIK" name="nik" minlength="6" type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'');" autocomplete="off" required inputmode="numeric"/>
								</div>
								<div class="fv-row mb-10">
									<div class="d-flex flex-stack mb-2">
										<label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
									</div>
									<div class="input-group">
										<input class="form-control form-control-lg form-control-solid text-center" placeholder="Password" type="password" name="password" autocomplete="off" required/>
										<button class="btn btn-outline-secondary" type="button" id="togglePassword">
											<i class='fa fa-eye' aria-hidden='true'></i>
										</button>
									</div>
								</div>
								<div class="fv-row mb-10 text-center">
									<div class="d-flex flex-stack mb-2">
									</div>
									<div class="captcha">
										<span>{{ captcha_img('math') }}</span>
										&nbsp;&nbsp;&nbsp;
										<button type="button" class="btn btn-sm btn-icon btn-primary btn-rounded" id="reload">
											<svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
												<path fill="#ffffff" d="M142.9 142.9c62.2-62.2 162.7-62.5 225.3-1L327 183c-6.9 6.9-8.9 17.2-5.2 26.2s12.5 14.8 22.2 14.8H463.5c0 0 0 0 0 0H472c13.3 0 24-10.7 24-24V72c0-9.7-5.8-18.5-14.8-22.2s-19.3-1.7-26.2 5.2L413.4 96.6c-87.6-86.5-228.7-86.2-315.8 1C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5c7.7-21.8 20.2-42.3 37.8-59.8zM16 312v7.6 .7V440c0 9.7 5.8 18.5 14.8 22.2s19.3 1.7 26.2-5.2l41.6-41.6c87.6 86.5 228.7 86.2 315.8-1c24.4-24.4 42.1-53.1 52.9-83.7c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.2 62.2-162.7 62.5-225.3 1L185 329c6.9-6.9 8.9-17.2 5.2-26.2s-12.5-14.8-22.2-14.8H48.4h-.7H40c-13.3 0-24 10.7-24 24z"/>
											</svg>
										</button>
									</div>
								</div>
								<div class="fv-row mb-10">
									<div class="d-flex flex-stack mb-2">
										<label class="form-label fw-bolder text-dark fs-6 mb-0">Captcha</label>
									</div>
									<input class="form-control form-control-lg form-control-solid text-center" placeholder="Masukkan Captcha" type="text" name="captcha" oninput="this.value=this.value.replace(/[^0-9]/g,'');" autocomplete="off" required inputmode="numeric"/>
								</div>
								<div class="text-center">
									<button type="submit" class="btn btn-lg btn-primary w-100 mb-5">Login</button>
								</div>
							</form>
						</div>
					</div>
					<div class="d-flex flex-center flex-wrap fs-6 p-5 pb-0">
						<div class="d-flex flex-center fw-bold fs-6">
							<p class="text-muted">©{{ date('Y') }} Workforce Management Common Resources</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script src="/assets/plugins/global/plugins.bundle.js"></script>
		<script src="/assets/js/scripts.bundle.js"></script>

		@include('partial.alerts')

		<script type="text/javascript">
			var passwordInput = document.querySelector('input[name="password"]');
			var togglePasswordButton = document.getElementById('togglePassword');

			function togglePasswordVisibility() {
				if (passwordInput.type === 'password') {
					passwordInput.type = 'text';
				} else {
					passwordInput.type = 'password';
				}
			}

			togglePasswordButton.addEventListener('click', function() {
				togglePasswordVisibility();
			});

			$('#reload').click(function () {
				$.ajax({
					type: 'GET',
					url: 'reload-captcha',
					success: function (data) {
						$(".captcha span").html(data.captcha);
					}
				});
			});
		</script>
	</body>
</html>