"use strict";!function(t){t("#addRow").click((function(){const n=t("#invoice-table tbody tr").length+1,e=`\n          <tr>\n              <td>${String(n).padStart(2,"0")}</td>\n              <td><input type="text" class="invoive-form-control" value="New Item"></td>\n              <td><input type="number" class="invoive-form-control" value="1"></td>\n              <td><input type="text" class="invoive-form-control" value="PC"></td>\n              <td><input type="number" class="invoive-form-control" value="0.00" step="0.01"></td>\n              <td><input type="number" class="invoive-form-control" value="0.00" step="0.01"></td>\n              <td class="text-center">\n                  <button type="button" class="remove-row"><iconify-icon icon="ic:twotone-close" class="text-danger-main text-xl"></iconify-icon></button>\n              </td>\n          </tr>\n      `;t("#invoice-table tbody").append(e)})),t(document).on("click",".remove-row",(function(){t(this).closest("tr").remove(),t("#invoice-table tbody tr").each((function(n){t(this).find("td:first").text(String(n+1).padStart(2,"0"))}))})),t(".editable").click((function(){const n=t(this),e=n.text().substring(1),o=t('<input type="text" class="invoive-form-control" />').val(e);n.empty().append(o),o.focus().select(),o.blur((function(){const t=o.val();n.text(" "+t)})),o.keypress((function(t){if(13==t.which){const t=o.val();n.text(":"+t)}}))}))}(jQuery);
