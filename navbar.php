<div class="container">
	<div class="navbar-header page-scroll row">
		<a href="/"><img alt="Logo de la JE" class="logo" height="40" id="logo" src="/img/logos/cnje.png"></a>
		<button class="navbar-toggle navbar-toggler ml-auto" data-target=".navbar-main-collapse" data-toggle="collapse" type="button">
            <i class="fa fa-2x fa-bars"></i>
        </button>
        <ul class="nav navbar-nav ml-auto">
            <li class="dropdown nav-item swag">
                <a href="" class="dropdown-toggle nav-link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    Je me forme
                    <span class="caret"></span>
                </a>
                <div class="dropdown-menu">
                    <?php if("$_SERVER[REQUEST_URI]" != "/"){$path = "/";}else{$path = "";} ?>
                    <a class="dropdown-item" href="<?php echo $path; ?>?query=themes">Formations par thème</a>
                    <a class="dropdown-item" href="<?php echo $path; ?>?query=date">Formations les plus récentes</a>
                    <a class="dropdown-item" href="<?php echo $path; ?>?query=views">Formations les plus vues</a>
                </div>
            </li>
            <li class="nav-item"><a class="nav-link" href="/forum/">Forum</a></li>
        </ul>
	</div>

    <div class="collapse navbar-main-collapse navbar-collapse ml-auto">
        <ul class="nav navbar-nav ml-auto">
            <li class="nav-item">
                <label>
                    <input type="search" placeholder="Rechercher" >
                    <button data-search="startSearch"><img src="<?php echo $img; ?>logos/search-icon.png" height="20"></button>
                </label>
            </li>
        </ul>
    </div>

	<div class="collapse navbar-main-collapse navbar-collapse ml-auto">

        <ul class="nav navbar-nav ml-auto">
			<li class="nav-item dropdown">
         		<a href="#" class="dropdown-toggle logo" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <img src="/img/logos/<?php
                    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                        if ($type == 0)
                            echo "group";
                        else
                            echo "louis";
                    } else {
                        echo "account";
                    } ?>.png" alt="image de compte" height="40" />
                    <!--
                        On change l'image en fonction du type de compte
                        Sachant que plus tard ce sera une image par compte
                     -->
                </a>
          		<ul class="dropdown-menu">
                    <?php
                        if($_SESSION['loggedin']) {
                           if($type == 0) {
                               echo "<a class=\"dropdown-item\" href=\"\">Mes membres</a>";
                           } else {
                               echo "<a class=\"dropdown-item\" href=\"\">Mes infos personnelles</a>";
                           }
                            echo "<a class=\"dropdown-item\" href=\"/disconnect.php\">Se déconnecter</a>";
                        } else {
                            echo "<a class=\"dropdown-item\" href=\"/accounts/index.php\">Se connecter</a>";
                        }
                    ?>
				</ul>
            </li>
		</ul>
	</div>
</div>
<script>
    $(function() {

        // the input field
        var $input = $("input[type='search']"),
            // clear button
            $nextBtn = $("button[data-search='startSearch']"),
            // the context where to search
            $content = $(".content"),
            // jQuery object to save <mark> elements
            $results,
            // the class that will be appended to the current
            // focused element
            currentClass = "current",
            // top offset for the jump (the search bar)
            offsetTop = 50,
            // the current index of the focused element
            currentIndex = 0;

        /**
         * Jumps to the element matching the currentIndex
         */
        function jumpTo() {
            if ($results.length) {
                var position,
                    $current = $results.eq(currentIndex);
                $results.removeClass(currentClass);
                if ($current.length) {
                    $current.addClass(currentClass);
                    position = $current.offset().top - offsetTop;
                    window.scrollTo(0, position);
                }
            }
        }

        /**
         * Searches for the entered keyword in the
         * specified context on input
         */
        $input.on("input", function() {
            var searchVal = this.value;
            $content.unmark({
                done: function() {
                    $content.mark(searchVal, {
                        separateWordSearch: true,
                        done: function() {
                            $results = $content.find("mark");
                            currentIndex = 0;
                            jumpTo();
                        }
                    });
                }
            });
        });

        /**
         * Clears the search
         */
        $clearBtn.on("click", function() {
            $content.unmark();
            $input.val("").focus();
        });

        /**
         * Next and previous search jump to
         */
        $nextBtn.add($prevBtn).on("click", function() {
            if ($results.length) {
                currentIndex += $(this).is($prevBtn) ? -1 : 1;
                if (currentIndex < 0) {
                    currentIndex = $results.length - 1;
                }
                if (currentIndex > $results.length - 1) {
                    currentIndex = 0;
                }
                jumpTo();
            }
        });
    });
</script>