<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Two Step Verification | Workforce Management Common Resources</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<link href="/assets/metronic8.2.6/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="/assets/metronic8.2.6/css/style.bundle.css" rel="stylesheet" type="text/css" />>
	</head>
	<body id="kt_body" class="bg-body">
		<script>var defaultThemeMode = "system"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
		
		<div class="d-flex flex-column flex-root">
			<div class="d-flex flex-column flex-lg-row flex-column-fluid">
				<div class="d-flex flex-column flex-lg-row-auto w-xl-600px positon-xl-relative" style="background-color: #F2C98A">
					<div class="d-flex flex-column position-xl-fixed top-0 bottom-0 w-xl-600px scroll-y">
					</div>
				</div>
				<div class="d-flex flex-column flex-lg-row-fluid py-10">
					<div class="d-flex flex-center flex-column flex-column-fluid">
						<div class="w-lg-600px p-10 p-lg-15 mx-auto">
							<form class="form w-100 mb-10" id="kt_sing_in_two_steps_form" method="POST">

                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $data->id }}">
                                <input type="hidden" name="password" value="{{ $data->password }}">
                                <input type="hidden" name="otp_valid_until" value="{{ $data->otp_valid_until }}">

								<div class="text-center mb-10">
									<img alt="Logo" class="mh-125px" src="/assets/media/svg/misc/smartphone.svg" />
								</div>
								<div class="text-center mb-10">
									<h1 class="text-dark mb-3">Two Step Verification</h1>
									<div class="text-muted fw-bold fs-5 mb-5">Enter the verification code we sent to your Telegram</div>
    								</div>
								<div class="mb-10 px-md-10">
									<div class="fw-bolder text-start text-dark fs-6 mb-1 ms-1">Type your 6 digit security code</div>
									<div class="d-flex flex-wrap flex-stack">
										<input type="text" class="form-control" name="otp" inputmode="numeric" />
									</div>
								</div>
								<div class="d-flex flex-center">
                                    <button type="submit" class="btn btn-lg btn-primary fw-bolder" id="submitBtn">Login</button>
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
		<script src="assets/plugins/global/plugins.bundle.js"></script>
		<script src="assets/js/scripts.bundle.js"></script>

        <script type="text/javascript">
			document.addEventListener("DOMContentLoaded", function () {
				const otpInputs = document.querySelectorAll('[name^="otp_code"]');
				
				otpInputs.forEach((input, index) => {
					input.addEventListener("input", function () {
						if (this.value.length === 1) {
							const nextInputIndex = index + 1;
							if (nextInputIndex < otpInputs.length) {
								otpInputs[nextInputIndex].focus();
							} else {
								document.getElementById('submitBtn').click();
							}
						}
					});

					input.addEventListener("keydown", function (event) {
						if (event.key === "Backspace" && this.value.length === 0) {
							const prevInputIndex = index - 1;
							if (prevInputIndex >= 0) {
								otpInputs[prevInputIndex].focus();
							}
						}
					});
				});
			});
		</script>
	</body>
</html>