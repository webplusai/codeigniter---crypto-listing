<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (strpos($_SERVER['REQUEST_URI'], '.php') !== FALSE) {
	$redirect = str_replace('.php', '.html', $_SERVER['REQUEST_URI']);
	header("Location: ".$redirect."", TRUE, 301);
	exit();
}

if (!function_exists('my_base_url')) {
	function my_base_url() {
		if (strpos($_SERVER['HTTP_HOST'], '.tv') !== FALSE) {
			return 'http://coinschedule.tv/';
		} else {
			return 'https://www.coinschedule.com/';
		}
	}
}

$icon_cslogo = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAV/QAAFf0BzXBRYQAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzIENTNui8sowAAASUSURBVFiFvZddbBRVFMd/Z7qWAqWr2ZnZ3dI2JbSCPkkw+AF+BE1MTDQGvyAQhUADoX4QiJjQ2G6b7AOfCgYwAcSgvumDJj4pPJDw9cCDiUZAIyCwbXcbZLtAYypzfOjuZvbuTFFSOcl9uOee8///75mZM/eiqvhHoxtfb/omagRhV0wStrM8HrPzScfdPNHkScfdHI/Z+YTtLA8UkLCd1fGYraWRdNxd4wEuW7yE1qbm+tam5volr752O/JdfuyE7awurUkxYLmqHsQwy7I+zmQH3ynNG934XFV9QVUXADOAe4tL14DzInJMRL7NZAdP+3J2eZ73toktIiv6c9nPJGE776nqFjPAJ2I78J2q9qjqU2FxBvhREekFnvc8b8M4cRslHrPvA7qA0MD/ybYDaVFVABK2sxtYe5fI9wwM5ToBrJKn6NjzX1BEBBG5Y3KAcgVKlrCdHcA6IAz5koh8qaqHo9HoEEA+n7dFZKGqLgOaQ/IU+GhgKLe+YhOmgEY3Ps/zvBP4qlMOFtlaW1vbe/HK5RtBDNMm1U2ub2joUdX3A5Y9q6ZmfmZw4OS4ApKOe1xVHzOzLcvqyGQH94fsztzEKs/z9gVs4GR/LluBHTES5wSRi8i225Gn+tL1wDcisjeTHdyfdNw2sxKq+mijG5/r7xOWEfBKAPaVurq6Tf+C/DCwUFV37v5k36SBodwm4LIZq6ov++cRY3G+mSAiB85f+mM0gNQFFgM1wOvAPCAHPNe5puOvzjUdJB33gKr2GKkL/JNyBR5ov99irL2ain8IIG8CfgR2AjuAR4CLwJOp7q6ffOK/D8Brnd3WXlMloFAoTAWm+QKxLItEInHNBAFWAgnDty/V3XXG7xCRK4BnxE0rFApTqgSUcsxZSKOJBPhuBgWGWBm0LCAajd4ACuUIEbxbHplMpiEA4CAwbPgWpfrSU/0OVZ1O9SavN0Qbyn2kvPjz2TOeiFwwmSzLetb0pbq7fgceAvqAXuAIYy/XiVRf2vEJeCZA/IVfzp27VZqYpTwGPGHsYtWstvb02d9+/dsQcR7oAUj1pe8BjgIxxlouD86aXaOqK012ETlesUFj8esAxU3D+XxvgN8vZhRYCMxJdXcNAfx59Wov0BIg4KuKeUArPqWq8wIS3+jPZT8fT4gPY6mqfhGAcbo/l33Y76v64ViWtY5iGf2mqoeSjts3o7llUhjxjOaW2qTjpoLIARWRd01n1eekqovCCFT1g5GRkaVJxz0kIkdU9VLxM21W1adV9U1gZlg+8BJj71nZKh5B2AFyHCs1mapKhpl50C0LuAPyOza/CGlpnB4dHR3d5HnexrtB7hOxJRKJpGum1E1+y/O88T6zD0WkV0RmEn7cMu2UiHQw9nesOl/A2J9XREakeCtaAXwaEFdxgGx044+r6ovAfFVtBeqLS9dF5CJQupiUX7Sw07ZlWZ2Z7OAe/7F8DbA3jNy0WW3tkcLw8BRVJRqN3jxjdEq/mSIsy9qQyQ7uKJWiPOIxe0U8Zg/HY/a2ib6cxmP21njMvp503LWht+Ni4MaJJvddUntM3z+WF1yG8JaRGQAAAABJRU5ErkJggg==';
$icon_blog = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAIAAAD8GO2jAAAAA3NCSVQICAjb4U/gAAAACXBIWXMAAAsSAAALEgHS3X78AAAAHHRFWHRTb2Z0d2FyZQBBZG9iZSBGaXJld29ya3MgQ1M26LyyjAAAA0FJREFUSIntlt9vFFUUx8+5d+783F9dtnW7tQUpEEhtiPBSgo0mEjUkaAwh8cEHE94hxkRjAol/gPBI9N0YjBofMMEHA8akxkiwMSkUMUAtbLel3ba7s7Oz8+Pew0MVTNzu0Mq+cV5mTu7M95PvvXPOGVyZ+KT550WKfOhCMHuLplo16VZIxd0AkIoZMg5cdEMdAJBprEvSD+MpIDG0jquIXHC7QCQp8lVQf9IARC09kD34PsV+VLsXVSaj2l/KX9kQKcEBM9P28CEAXMtVq+5OnW9c/1q5FZLh4wASzoBkELuVR0+bmey+48WjnzvPvw30OPqdHZCKqrfmzx8FrmuZAWvwQGrkGE8VudOXGzth9I0sXzpFMvofAAClCGWkvCXVmA/nrjZuXLC3v5IbO8GMtL3rMAqr+v0HpFodFPiH774cLt2Atr0ImcgPF14/k9r9hjk4hkyE5V/D+cmoelPkhrR0SeR3aJlS89YPuI46E3bCIWtO3iztX0vMoXF7x2u1q5/5dy6p1mp2/COzuNfZ/WawcM2b+mK9veroAABIobBlc5FbPdzKiy07jeJouHw7mPtNNubsreMoLKM46s/8qJrVtg4SACqo+zOXW7MT0fJtAhLZrVqqaPTvD8pXgsokAFpDB1EzKXDDhan/frhM2EmtApEApL/i/fHd0sX3vGtfUdQUPduyB04C092pL2O3AkDOnre0zEBbgaQ6YFz0jaT3vmNtP8RQrP581p/5CQDM/hfsbS9C5DWnvyEZc6eXp0ptK6MzAJmee+bIp/mXTvcdOWcOvyoD17t5gWTIrLy96zCQ8mcnQEUAoPfuQW5s2IFwCszM/X2fG0CE6P71VvkKAIie5wAoqt5ZG7c8U2JmesOA2C237v4CABT7rblJIpD+ivIWAYCZOdDTJH1EBADu9KKw2mzC8uWPG9Pfrv9Xgdwp8FQ/hY24XiYZEICWGxTpZ2VQD5emkUDvHWW6EflVWbsHcfDvl7ldSGgVACS9RektPgICyNVZuXp3bRUAw4XfAYGAENpU9OYmGv5zwYdZW/VNAzYQTwHJACICpbolT6ShZjA9pWLeDXnUHc3o3wdMozgAXG8ubVpfMiP7AJggbEnXD5tBAAAAAElFTkSuQmCC';
$icon_telegram = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAADSklEQVRYR+1WSUyTYRB9LW3pDhRKgVKxLGpcUAIIhCJijCHxYvTkgeiBhIMSlwMSExPlqNFo4sWrxkTjhhGM4AECRi81BuICiKxSkLKUUlpoS2vmJ4VudLGAl07SQ9uZb97MvHnfx6p+2u/EfzRWFEC0A1vVgXgBB3wOCxPzNg/KbyoJOWwW9qeJoMmUYI9CCCecqG8awazFvgpiUwAo43jQqKUozhBDHBvjUfHVtyPQm9a6sGEABFw2ClViaDKlUMti/SqL2erAxcZBuAtPxABy5Hym2gKVGLwY1mpi09IytL8XcDhLuvrbtwkz7naMR86BOH4MSrZLmMQKCden2o9D83jfZ0B1kQI0Dpc1f59F49eZfwPAZrGQmyaERi3BvlQh6Lu3TZpseKTVw2Cx41J5GmRCjofL/Q8T6NIthAeAKqRKqWKq3J8tO5x412NA849ZbIuPRW1ZKkQ8to/r5ddDmF9aDg4glsNGfroIZZlSZCfxA15V/VOLTNU6o5XpUE1JigcXXMHTZjvqm4Z9zvIgIbX1ZK4M5VlS8Dm+FbhHW2wOvOieRscvI8NqGk1VgdzvaChOO2rCg09/AgNIEHBw7Vg6JF676x1Fhz35MoW5xZV2Ht+dgBN7ZQE79axrGq29hsAA6F9Sr13JAhxQipiP+9xnzHY8/qxH97iZOYh4eDovCRXZcQGT058328bwU78YHIC7B/FcnchHnlKEimwp6t4Mw2xzMC4EtLo4Gfnp4qDJHU6g9uUArMu+b5+Qhei8JgVt/UaQmJDqnStNwc5kQdDk5DBqsKKhddSvb8gAaA1zkvh4qNUzAlOUEbxyV8bOASMT589CBiDksnGjUoUrTcOoO6JEVmLg9XRPRskJREQAKPjCoVS09BhwplCOJNGKBNNc3e8Af0mut4xibM4aOQASJlU8D6VqKZOU1vBOuw7KeB6q8uUMN7xtye5A7atBONd5e4c8AjqY7vaGShWjE6T3t9t1qy8cuZiLmhIFMhI8r+I+vQW32nTrkjUsAK4xyEVc3Osc93hYuFbzVG4iju5Y04WWXgOed01vHIBQ9o6eYWcPyiHmxTBd6pm0bC0AykZ8oOt4PfK5EIU9glA6EI5PFEC0A38BZulSMPIgYboAAAAASUVORK5CYII=';
$icon_slack = 'data:image/png;base64,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';
$icon_twitter = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAADAklEQVRYR+2WS0hUURjH//c1j+vojFnoqGkqJQZqL4Mco1ooZQ+IILBFKG2i2tUiKKiNi4jATbuCXAUZlChJhWWki7CItCFLwlIzHwzivO/MfcSZcjHOnXuPIrSZb3eZM+f8vv/3ZNC1pOE/GpMByCiwngrYOKBUZBFWgKmwSpXaukkocgzCCn1xbLIyuFVtQ8tmAQSC2ERIRfuYhPsT8cR3ZTaLWieHR9N/v5ctBaBEZNHjsePgmzAWY+YQbhuDoUNZKMtidT3um5VBAKudHPb2hzCypBgDnCsTcG+3HaNLKo4MhvArYgzRXS/iRCFvKHdQ1nDts5RQ9fmckhSeFAWubLPgdo0tceG8pKFtOIJns7LuA8V2FpNHHWBMok1cIGeueyW0f5GMFThVJODxPnvSoaczMm56JXxaId8xN48ej2iabKoGXB6JomM8lnI2RQEHz2Cy2YFcS6pfQz4FXdNxvF1QMOpX0JTPo5cCwB/X4OwO6ILqVsHZUgGddckqrPw38cova3AJZgEAZqMa3L2UABcqLNjp4rA9h0V93r+aMhXZ+MCHRQV7+kN0Chwu4NHXYB7X1TA9+BFH2/sIHQDHAN4mR6JxrJe1DkfQ+TO5AaVtROSHXS4Orw6IcFLE1wwyriIRf1+appZ2H9jqYHGn1obmAh5ElbXaw6k4zrzTl5/cmRbgYoUFLSUCqrJZbNApSRogUik1L4Pw+tMPprQAhXYGI40O5K3xcQJ493sMlz5GDVkNV7K6XA7dHhFk4KzWxgIq6vpDIHPAyEx3wmyeQesWAY35PI67jYfO8kMLkob9AyF8DZjvBKYA5FLScjtqbajKMS/N6YiK5sFwYprSWBIAmQMiB1g5BmUiA89GHqeLeexw0XXEF3MySM3/jprvEbp9QGCB8+UWXK20giQhrY0HVdzwSiAlt1rTDQEBIfV/skhAQx6HcgebNPNjKvAtqGJgXsaTGRmv52XQ+5yMSJUDZM/Lt7IgYAFZg0/SYJLc1EJQAVDftoaDGYCMAn8Aj3c+kKSP9b0AAAAASUVORK5CYII=';
$icon_facebook = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAABiklEQVRYR2O0jpzxn2EAAeOoAygNAR4uNgZTXRkGWQl+BnY2FoZff/4yfP32i+HctWcM9x6/Ixi5FEVBpI8+Q1KQMQMnByuGRbNXn2ZYuP4c7RwQH2DEkBpmitMCmjpARJCbYfWESAZWVuaBcUCgqzZDcaINiuU/fv5hOHPlCcO3H7/B4vtP3GM4fPYBbaKgOMmWIdBFC8XwpOq1DLfuvyFoIboCshJhQ44zg4uVCtysL99+MXikzCfZcpAGshzQmOvC4GypDLfw45cfDN5pC4exA5TlhBlU5ITgPgxy02bQVhGH87//+M3QM+8wSgicv/aM4dW7rwRDhagoSAo2YUgKNiZoGLKCtLr1DNfuvCKoh2YO8MlYyPDh04+BcQCoTHBJnEvQcqJzAalRAKqE4spXU88BoMoGucIpTbZlsDVWgFvw6ctPhpiyVXD+nz9/GUBixACi0gC6QSOvHBgNgdEQGA2B0RBADwFHcyUGRRlE++Dnrz8MSzdfIKbkxVBDVlFMlk04NI06AAA0C/yBxHdz8QAAAABJRU5ErkJggg==';
$icon_reddit = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAE4klEQVRYR+2WeWwVVRTGfzNv5m19rywCgrYSBKKlGCoFDCigIkWIIoJFCRbZhCKLRZA2VhRQFiUFFCwGrVYMqRJcEMFElkQwgGJRQZuwyVqgZW3f0rfMmzFzH5TWFlvSGv/h/jUz98493/3Od75zpUsp7Q3+xyHdBHCTgf+KAbl5K5RufcDQCf+0DaP8cq1Sb7gIJRm19wCUzt1B14kcKcLwXMaZtQzJ4RRBDW8Z3sw0IoeLaoBoMADnjEVYU57CqPCDLCHZHCKIXnoa36vjwGbHNf9jIscO4H15ZOMCUBKScC1bR2jTZ/hXvA66gZKYjCungNDGAvzvzhYBY7KXoyT1pCy1W+MCsI+egX3EJMrT+ogTXx3u3A3ILdvgX5IJqg3nzLeIFP2KN2tU4wJwZi1FvT+FssGdwbhmqJa7uuBa8BGSq0k0HRdL8WWOInLicAMASDLKPd1QuvbG0jERS+s45FvjQLWinzqKXlKMdnA/WuF2tD8KkdyxqMm9MXQdbc8PGH5v/arA3NSRno3SqStGhY/QtvXoJaexj3gBuU18VNWXzhMpPlpZWpK7KZa4dkjNWkRPfOYkgc9XYolvj/WRoWC3o+3dSUXuPPTS4mpAqlWB5IjBvWoTcrOWhHduRr69HZYOnaKbnj5O8Ns14rsZoCaXEnLreJES22MjK8FGDu4X7Kg9+6FfKMEzcVC0Yq6MagCsA4fjzFiA740pmD+6lq5Fbtqciry3Ca5fDZFI/fqmomB7cgyO0TPQL5/HO/1pLAlJxLzyDv6cLELfr6sdgP2ZdOxjZuKZ9DjO6fOxtLsbb/ZYtN93ix9EjdvtGGWXas9nbFMIBjGCFWJeSX4A17wPiRz5E/+KObiXf0XFqoUEv8irHYAZ0L1yA/rFc8i3tBI5EycHLHd0EIxIzhj8i2cJbVQd1pRhOF9ahOErx/NiKvqpv8S0LfV5HOMz0S+UIjdvgWfCoGrVUMMJbYOfxTFpNkYwQFlqdwiHohsNHYNjYrZ4Du/agm9OejUAMW/moXbvK75VvDeX4DefXmHNTuzaPUiKij93rjCoqqNWK3a/vxFCATzThlWulVvdhmvhJ0hNmuFbkIG298dqG6k9HsKZmYNh5jwzDf18SeW8ST2yjGfyEzW1W1s3FFS7m+AZP6B+oquUtBR9qmJK5mts/jb0c2fq3wsc42ZhGz4Bz4SBRI4fuiYYRwxYLBje8uuLMBwW/nF1WNonYFpzoCCXQP6S+jEgt44jNm8z2oF9gs6rOoiZvQIlqRfBr/MJ79qKfvYkKKoQqNqrP9ZHhwvX882fGg2kWnEtXoPSIZHysf1rmJC55LrtWNRxejbhX7bjX5ghTm2akmPKXJSEe2syYBiEC3cQ+GARkWMHRR8w694sxarV9M8fr38fkCTsI6diT5smBBVYvSxaeuEQlrYdsSQmi1JFCwtn1Pb9LJqOZLWh9huC/bkM4aiB/BwCBSuvq6U6LyRqjwdxTJ6DmRbzWhXevRWtqBC9+Fi0F0gSZi+Q4+8UdwFzvfmunzmBf/lraIXVq6X+DFRdqahY+w7C+vAQlC73idzWOrQw2m+7CW35ktCO70DT6qyiOhmogdhqQ27bUTAS7feG0Id+9hT68UMYoWCdQes0ohvaoYGLb5iBBsarnw80dpB/2+8mA38Dv5IlHxfQfX0AAAAASUVORK5CYII=';
$icon_linkedin = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAIAAAD8GO2jAAAAA3NCSVQICAjb4U/gAAAACXBIWXMAAAsSAAALEgHS3X78AAAAHHRFWHRTb2Z0d2FyZQBBZG9iZSBGaXJld29ya3MgQ1M26LyyjAAAAr9JREFUSIndVktPE1EUPufeebQDaWtbgoKgBhoxMVE3BqMLYwhGE3cu9C+4dWHiQhcs9B+4cuPGf2CITdwY44OEBQuUhwmkAkoptDBt53XvcTEtrWVKdQILPKt5nu9+97vfOQejT7NVV8KhBYsqHPGwsiMCO6zc9Tj6AEq7F5yhoXIEKLtCSDp4gEzKGB9OMYQ33zbmNysHDNAf0yfGhu+e7wWAOyOb917P5KtOOIBgDYaSRiZl1MDieiYdDZe9LcDcenl6ddu//pIrzeZNgJAyYHLi3ZblUvPvBACQ7lIvnoh5kmZ+7mxWHYAwbkQM1AAporC+mG55wvLkYCJSsj2VwZXBY73dGhF8zZdn182rpxLnerp1BXds8WG5OLdRDsQIFBmThvJsPHP7bA8AvF/auvFyKh5Rnt/MXD4ZB4C3C4XJhY2H1073x3T/h0+54pPs9+xiYS/PIAACDRWF1eTRODJEznhMr308nkmNDSddQZYndc4QYXQg8fj6mZWSPZs3WzCCREaQRLIuCwEQSCLpiEbRXdqyXnzOvZpe/WXa/pNLfbFbI2nOWym0Ndo+sVCoPJqczy4Wqp4sWt6D0YFujccjylAyGlFY2RGdGHSKXMn6mCuZjhCSpn6U1rZrJBKRgNIfBsB2pevVtst0hV3fOs4CjvJfA1BjbaxpmQxh9y7Qiv/AAEN1vrYA2HLcsLG+ZqAmAsFeDwIgYAi7+4l+FgS1fgRV1iCDSLqy6xi2l2RwqXBJFqqOkGQLWai4UpInaGXbThuaJ2nNtEXdJRVHLBerPV2axtnqjr23MwUVOwBEUBnjDABAEvgW0zhjCERAAI6QRIAIDFFh6NMVElwpm1O1KXYA5Cf9wzFge63jExEIov0b6tGfKv4DAE8ShZ96OgQRKClDZRi2o3cKBFDuXzjuV3AMPTi0SU0Akug3pogo2DOxr1sAAAAASUVORK5CYII=';

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1">
	<meta name="robots" content="index, follow" />
	<title>Coinschedule - 404</title>

	<link rel="stylesheet" type="text/css" href="<?=my_base_url()?>public/css/404style.css">
</head>
<body>

<div class="navbar">
	<div class="container">
		<div class="nav-container">
			<div style="float: left;">
				<a href="<?=my_base_url()?>">
					<div class="logo inline">
						<img src="<?=$icon_cslogo?>" alt="Coinschedule Logo">
						<div class="logo-text"><span style="color: #77797D">Coin</span>Schedule</div>
					</div>
				</a>

				<div class="mediasquares inline">
					<div class="hide">
						<a href="<?=my_base_url()?>blog/" target="_blank">
							<img src="<?=$icon_blog?>" alt="Blog Link Icon">
						</a>
					</div>

					<div>
						<a href="http://bit.ly/2zY5yCj" target="_blank">
							<img src="<?=$icon_telegram?>" alt="Telegram Link Icon">
						</a>
					</div>

					<div>
						<a href="<?=my_base_url()?>slack.html" target="_blank">
							<img src="<?=$icon_slack?>" alt="Slack Link Icon">
						</a>
					</div>

					<div class="hide">
						<a href="https://twitter.com/coinschedule" target="_blank">
							<img src="<?=$icon_twitter?>" alt="Twitter Link Icon">
						</a>
					</div>

					<div class="hide">
						<a href="https://www.facebook.com/Coinschedule-673455652847426/" target="_blank">
							<img src="<?=$icon_facebook?>" alt="Telegram Link Icon">
						</a>
					</div>

					<div class="hide">
						<a href="https://www.reddit.com/user/coinschedule/" target="_blank">
							<img src="<?=$icon_reddit?>" alt="Reddit Link Icon">
						</a>
					</div>

					<div class="hide">
						<a href="https://www.linkedin.com/company/coinschedule" target="_blank">
							<img src="<?=$icon_linkedin?>" alt="LinkedIn Link Icon">
						</a>
					</div>
				</div>
			</div>

			<input type="checkbox" id="nav-trigger" class="nav-trigger" onChange="$('.usermenu-trigger').prop('checked', false);"/>
			<label for="nav-trigger">
                <span class="burger inline">
                  <span></span>
                  <span></span>
                  <span></span>
                </span>
			</label>

			<div class="nav inline">
				<div class="nav-item inline"><a href="<?=my_base_url()?>">ICOs</a></div>
				<div class="nav-item inline"><a href="<?=my_base_url()?>#blog">Blog</a></div>
				<div class="nav-item inline"><a href="<?=my_base_url()?>#events">Events</a></div>
				<div class="nav-item inline"><a href="<?=my_base_url()?>icos.html">Results</a></div>
				<div class="nav-item inline"><a href="<?=my_base_url()?>stats.html">Stats</a></div>
				<div class="nav-item inline"><a href="<?=my_base_url()?>submit_entry.html">Submit</a></div>
				<div class="nav-item inline"><a href="<?=my_base_url()?>advertise.html">Advertise</a></div>
				<div class="nav-item inline"><a href="<?=my_base_url()?>login.html">Login</a></div>
			</div>
		</div>
	</div>
</div>

<div class="container">

	<div class="header"></div>
	<div class="main">
		<section style="text-align: center; min-height: 250px; margin-top: 120px;">
			<h1>Whoops!</h1>
			<h2>404: We could not find what you were looking for</h2>
			<p>Please check the link and try again.</p>
		</section>
	</div>

	<div class="footer">
		<div class="footer-col inline top">
			<h4>Website</h4>
			<a href="<?=my_base_url()?>">Home</a>
			<a href="<?=my_base_url()?>about.html">About</a>
			<a href="<?=my_base_url()?>submit_entry.html">Submit Entry</a>
			<a href="<?=my_base_url()?>advertise.html">Advertise</a>
			<a href="<?=my_base_url()?>login.html">Login</a>
		</div>
		<div class="footer-col inline top">
			<h4>Tools</h4>
			<a href="<?=my_base_url()?>icos.html">Results</a>
			<a href="<?=my_base_url()?>stats.html">Stats</a>
			<a href="https://developers.coinschedule.com/" target="_blank">Developers (API)</a>
		</div>
		<div class="footer-col inline top">
			<h4>Media</h4>
			<a href="<?=my_base_url()?>blog">Blog</a>
			<a href="<?=my_base_url()?>press.html">Press Mentions</a>
			<a href="https://twitter.com/coinschedule">Twitter</a>
			<a href="https://www.facebook.com/Coinschedule-673455652847426/">Facebook</a>
			<a href="https://join.slack.com/t/coinschedule/shared_invite/MjM2NjY2NDk4NzA0LTE1MDQ3NDEwMzYtZjFiYTVmOGUyZA">Slack</a>
			<a href="https://www.reddit.com/user/coinschedule/">Reddit</a>
			<a href="https://www.linkedin.com/company/coinschedule">Linkedin</a>
		</div>
		<div class="footer-col inline top">
			<h4>Legal</h4>
			<a href="<?=my_base_url()?>terms.html">Terms</a>
			<a href="<?=my_base_url()?>disclaimer.html">Disclaimer</a>
			<a href="<?=my_base_url()?>privacypolicy.html">Privacy Policy</a>
			<a href="<?=my_base_url()?>cookies_policy.html">Cookie Policy</a>
		</div>
	</div>

	<div class="copyright">
		<div class="inline">Copyright &copy; <?=date('Y');?> <a href="https://www.coinschedule.com">Coinschedule.</a></div>
		&nbsp;
		<div class="inline dmca">
			<a href="//www.dmca.com/Protection/Status.aspx?ID=18ff9b78-666a-445b-8b50-96af4895f2a0" title="DMCA.com Protection Status" class="dmca-badge" target="_blank">
				<img src="<?=my_base_url()?>public/images/img/dmca-badge-w150-5x1-08.png" alt="DMCA.com Protection Status">
			</a>
			<script src="//images.dmca.com/Badges/DMCABadgeHelper.min.js"> </script>
		</div>
		<div class="stretch"></div>
	</div>

</div>
<div style="display: none">404 error</div>
</body>
</html>
