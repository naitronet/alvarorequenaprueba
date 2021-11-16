<header>
	<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
		<div class="container">
			<a class="navbar-brand mr-5" href="/">APP Fútbol</a>

			<button class="navbar-toggler" type="button"
				data-toggle="collapse"
				data-target="#navbarSupportedContent"
				aria-controls="navbarSupportedContent"
				aria-expanded="false"
				aria-label="Activar navegación"
				><span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="nav nav-pills align-items-center">
					<li class="nav-item">
						<a class="nav-link <?=$_SERVER['SCRIPT_NAME'] === '/index.php' ? 'active' : ''?>" href="/">
							Inicio
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?=stripos($_SERVER['SCRIPT_NAME'], '/clubs/') !== FALSE ? 'active' : ''?>" href="/clubs">
							Clubs
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?=stripos($_SERVER['SCRIPT_NAME'], '/jugadores/') !== FALSE ? 'active' : ''?>" href="/jugadores">
							Jugadores
						</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
</header>