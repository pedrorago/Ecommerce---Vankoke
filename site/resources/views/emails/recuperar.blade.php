<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="pt-br">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1"> <!-- So that mobile will display zoomed in -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- enable media queries for windows phone 8 -->
	<meta name="format-detection" content="telephone=no"> <!-- disable auto telephone linking in iOS -->
	<title>Emails</title>

	<style type="text/css">
		body {
			margin: 0;
			padding: 0;
			-ms-text-size-adjust: 100%;
			-webkit-text-size-adjust: 100%;
		}

		table {
			border-spacing: 0;
		}

		table td {
			border-collapse: collapse;
		}

		.ExternalClass {
			width: 100%;
		}

		.ExternalClass,
		.ExternalClass p,
		.ExternalClass span,
		.ExternalClass font,
		.ExternalClass td,
		.ExternalClass div {
			line-height: 100%;
		}

		.ReadMsgBody {
			width: 100%;
			background-color: #ebebeb;
		}

		table {
			mso-table-lspace: 0pt;
			mso-table-rspace: 0pt;
		}

		img {
			-ms-interpolation-mode: bicubic;
		}

		.yshortcuts a {
			border-bottom: none !important;
		}

		@media screen and (max-width: 599px) {
			.force-row,
			.container {
				width: 100% !important;
				max-width: 100% !important;
			}
		}
		@media screen and (max-width: 400px) {
			.container-padding {
				padding-left: 12px !important;
				padding-right: 12px !important;
			}
		}
		.ios-footer a {
			color: #aaaaaa !important;
			text-decoration: underline;
		}
	</style>


</head>

<body style="margin:0; padding:0;" bgcolor="#F0F0F0" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

	<!-- 100% background wrapper (grey background) -->
	<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#F0F0F0">
		<tr>
			<td align="center" valign="top" bgcolor="#F0F0F0" style="background-color: #F0F0F0;">

				<br>

				<!-- 600px container (white background) -->
				<table border="0" width="600" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px">
					<tr>
						<td class="container-padding header" align="left" style="font-family:Helvetica, Arial, sans-serif;font-size:24px;font-weight:bold;padding-bottom:12px;color:#8D6CC9;padding-left:24px;padding-right:24px;text-align:center;">
							<img src="{{ config('app.url') }}img/logo_email.png" style="width:300px;">
						</td>
					</tr>
					<tr>
						<td class="container-padding content" align="left" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">
							<br>

							<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">
							Prezado(a) {{ $name }} </div>

							<br>
							
							<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
								<p> Perdeu a sua senha ? clique no link abaixo para recupera-la. </p>
								<a href="{{ config('app.url') }}resetar_senha?email={{ $email }}&tk={{ $token }}" style="margin-bottom:30px;">Recuperar Senha</a>
								<p style="margin-bottom:30px;"> Caso não tenha feito essa solicitação, é provável que outro usuário tenha inserido o seu endereço de e-mail por engano, neste caso favor desconsiderar. </p>
								<p style="margin-bottom:30px;"> Atenciosamente, </p>
								<p> {{ config('app.name') }} </p>
							</div>

						</td>
					</tr>
				</table>
				<!--/600px container -->
			</td>
		</tr>
	</table>
	<!--/100% background wrapper-->
</body>
</html>