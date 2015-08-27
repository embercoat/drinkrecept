<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template name="savebar">
    <form action="/backend/savebar/" method="post">
        <table border="0">
            <tr>
                <td><label for="barname">Barens Namn</label></td>
                <td><input type="text" id="barname" name="barname" /></td>
            </tr>
            <tr>
                <td><label for="password1">Lösenord</label></td>
                <td><input type="password" name="password1" id="password1" /></td>
            </tr>
            <tr>
                <td><label for="password2">Bekräfta</label></td>
                <td><input type="password" name="password2" id="password2" /></td>
            </tr>
            <tr>
                <td><input type="submit" value="Spara" /></td>
            </tr>
        </table>
    </form>



</xsl:template>
</xsl:stylesheet>
