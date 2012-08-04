/*
 * Required for the BBCode (Hooker)
 */
function xs_show_hide(id1, id2, id3) 
{
	var res = xs_exp_menu(id1);
	if (id2 != '') xs_exp_menu(id2);
//	if (id3 != '') ca_cookie_set(id3, res, exp);
}
	
function xs_exp_menu(id) 
{
	var itm = null;
	if (document.getElementById) 
	{
		itm = document.getElementById(id);
	}
	else if (document.all)
	{
		itm = document.all[id];
	} 
	else if (document.layers)
	{
		itm = document.layers[id];
	}
	if (!itm) 
	{
		// do nothing
	}
	else if (itm.style) 
	{
		if (itm.style.display == "none")
		{ 
			itm.style.display = ""; 
			return 1;
		}
		else
		{
			itm.style.display = "none"; 
			return 2;
		}
	}
	else 
	{
		itm.visibility = "show"; 
		return 1;
	}
}
