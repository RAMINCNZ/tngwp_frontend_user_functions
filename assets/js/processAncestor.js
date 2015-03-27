function processAncestor() {
	if (document.getElementById("relation").selectedIndex == 0) {//self
		document.getElementById('parents').style.display = 'none';
		document.getElementById('grandparents').style.display = 'none';
		document.getElementById('gr_grandparents').style.display = 'none';
	}
	if (document.getElementById("relation").selectedIndex == 1) {//spouse
		document.getElementById('parents').style.display = 'none';
		document.getElementById('grandparents').style.display = 'none';
		document.getElementById('gr_grandparents').style.display = 'none';
		document.getElementById('spouse').style.display = 'block';
	}
	if (document.getElementById("relation").selectedIndex == 2) {//father
		document.getElementById('parents').style.display = 'none';
		document.getElementById('grandparents').style.display = 'none';
		document.getElementById('gr_grandparents').style.display = 'none';
	}
	if (document.getElementById("relation").selectedIndex == 3) {//mother
		document.getElementById('parents').style.display = 'none';
		document.getElementById('grandparents').style.display = 'none';
		document.getElementById('gr_grandparents').style.display = 'none';
	}
	if (document.getElementById("relation").selectedIndex == 4) {//sister of father
		document.getElementById('parents').style.display = 'block';
		document.getElementById('grandparents').style.display = 'none';
		document.getElementById('gr_grandparents').style.display = 'none';
	}
	if (document.getElementById("relation").selectedIndex == 5) {//sister of mother
		document.getElementById('parents').style.display = 'block';
		document.getElementById('grandparents').style.display = 'none';
		document.getElementById('gr_grandparents').style.display = 'none';
	}
	if (document.getElementById("relation").selectedIndex == 6) {//brother of father
		document.getElementById('parents').style.display = 'block';
		document.getElementById('grandparents').style.display = 'none';
		document.getElementById('gr_grandparents').style.display = 'none';
	}
	if (document.getElementById("relation").selectedIndex == 7) {//brother of mother
		document.getElementById('parents').style.display = 'block';
		document.getElementById('grandparents').style.display = 'none';
		document.getElementById('gr_grandparents').style.display = 'none';
	}
	if (document.getElementById("relation").selectedIndex == 8) {//brother
		document.getElementById('parents').style.display = 'block';
		document.getElementById('grandparents').style.display = 'none';
		document.getElementById('gr_grandparents').style.display = 'none';
	}
	if (document.getElementById("relation").selectedIndex == 9) {//sister
		document.getElementById('parents').style.display = 'block';
		document.getElementById('grandparents').style.display = 'none';
		document.getElementById('gr_grandparents').style.display = 'none';
	}
	if (document.getElementById("relation").selectedIndex == 10) {//grandfather
		document.getElementById('parents').style.display = 'block';
		document.getElementById('grandparents').style.display = 'none';
		document.getElementById('gr_grandparents').style.display = 'none';
	}
	if (document.getElementById("relation").selectedIndex == 11) {//grandmother
		document.getElementById('parents').style.display = 'block';
		document.getElementById('grandparents').style.display = 'none';
		document.getElementById('gr_grandparents').style.display = 'none';
	}
	if (document.getElementById("relation").selectedIndex == 12) {//great-grandfather
		document.getElementById('parents').style.display = 'block';
		document.getElementById('grandparents').style.display = 'block';
		document.getElementById('gr_grandparents').style.display = 'none';
	}
	if (document.getElementById("relation").selectedIndex == 13) {//great-grandmother
		document.getElementById('parents').style.display = 'block';
		document.getElementById('grandparents').style.display = 'block';
		document.getElementById('gr_grandparents').style.display = 'none';
	}
	if (document.getElementById("relation").selectedIndex == 14) {//2gr-grandfather
		document.getElementById('parents').style.display = 'block';
		document.getElementById('grandparents').style.display = 'block';
		document.getElementById('gr_grandparents').style.display = 'block';
	}
	if (document.getElementById("relation").selectedIndex == 15) {//2gr-grandmother
		document.getElementById('parents').style.display = 'block';
		document.getElementById('grandparents').style.display = 'block';
		document.getElementById('gr_grandparents').style.display = 'block';
	}
	if (document.getElementById("relation").selectedIndex == 16) {//select a relationship
		document.getElementById('parents').style.display = 'none';
		document.getElementById('grandparents').style.display = 'none';
		document.getElementById('gr_grandparents').style.display = 'none';
	}
};