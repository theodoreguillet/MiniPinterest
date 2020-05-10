<div class="container-fullwidth navidation">
	<nav class="navbar navbar-expand-lg navbar-light">
		<a class="navbar-brand" href="index.php">
			<img src="assets/img/logo.svg" width="30" height="30" class="d-inline-block align-top" alt="logo">
			Pic
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar5">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="navbar-collapse collapse" id="navbar5">
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link <?php if ($CURRENT_PAGE == "Index") {?>active<?php }?>" href="index.php">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?php if ($CURRENT_PAGE == "Themes") {?>active<?php }?>" href="themes.php">Themes</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?php if ($CURRENT_PAGE == "About") {?>active<?php }?>" href="about.php">About</a>
				</li>
			</ul>
			<form class="mx-2 my-auto d-inline w-100">
				<div class="input-group">
					<input type="text" class="form-control border border-right-0" placeholder="Search..." 
						<?php 
						if(isset($PICTURES_THEME_NAME)) {
							echo 'value='.$PICTURES_THEME_NAME;
						}
						?>
					>
					<span class="input-group-append">
						<button class="btn btn-outline-secondary border border-left-0" type="button">
							<i class="fa fa-search"></i>
						</button>
					</span>
				</div>
			</form>
			<ul class="navbar-nav">
				<?php
				if($USER_IS_MODERATOR) {
					echo '
					<li class="nav-item">
						<a class="nav-link '.($CURRENT_PAGE == 'Stats' ? 'active' : '').'" href="stats.php">Stats</a>
					</li>
					';
				}
				?>
				<li class="nav-item">
					<a class="nav-link" href="upload.php">
						<i class="fa fa-plus-square fa-lg"></i>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="profile.php">
						<img class="user-nav-image" src="<?= $USER_IMAGE ? $USER_IMAGE : 'assets/img/user.svg' ?>"></img>
					</a>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-angle-down"></i>
					</a>
					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
						<form action="logout.php" method="post">
							<input class="dropdown-item" type="submit" value="Disconnect">
						</form>
					</div>
				</li>
			</ul>
		</div>
	</nav>
</div>