/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Plugins
 * @license    LGPL
 * @filesource
 */


/**
 * Class Request.Contao
 * 
 * Extend the basic Request.JSON class.
 * @copyright  Leo Feyer 2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Plugins
 */
Request.Contao = new Class(
{
	Extends: Request.JSON,

	options: {
		'url': window.location.href
	},

	initialize: function(options) {
		if (options) {
			// Try to replace the URL with the form action
			try	{
				this.options.url = options.field.getParent('form').getAttribute('action');
			}
			catch(e) {}
		}
		this.parent(options);
	},

	success: function(text) {
		var json;

		// Support both plain text and JSON responses
		try	{
			json = this.response.json = JSON.decode(text, this.options.secure);
		} catch(e) {
			json = {'content':text};
		}

		// Empty response
		if (json == null) {
			json = {'content':''};
		}

		// Isolate scripts and execute them
		if (json.content != '') {
			json.content = json.content.stripScripts(function(script) {
				json.javascript = script.replace(/<!--|\/\/-->|<!\[CDATA\[\/\/>|<!\]\]>/g, '');
			});
			if (json.javascript && this.options.evalScripts) {
				$exec(json.javascript);
			}
		}

		this.onSuccess(json.content, json);
	}
});

// Backwards compatibility
Request.Mixed = Request.Contao;
