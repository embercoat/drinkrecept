<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet [
    <!ENTITY Auml "&#196;">
    <!ENTITY Aring "&#197;">
    <!ENTITY Ouml "&#214;">
    <!ENTITY auml "&#228;">
    <!ENTITY aring "&#229;">
    <!ENTITY ouml "&#246;">
    <!ENTITY amp "&#38;">
    <!ENTITY nbsp "&#160;">
]>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <!-- Edit Ingredient -->
    <xsl:template name="editIng">
    <form action="/edit/submitIng/" method="post">
    <input type="hidden" name="iid" id="iid"><xsl:attribute name="value"><xsl:value-of select="/root/ing/iid" /></xsl:attribute></input>
    <table border="1" style="width:100%;">
        <tbody>
            <tr>
                <td colspan="2">
                    <a style="float: right;">
                        <xsl:attribute name="href">
                            /ing/<xsl:value-of select="/root/ing/iid" />
                        </xsl:attribute>[Visa]
                    </a>

                    <h1 style="text-align: center; font-size: 25px;">
                        <input type="text" name="ingName" id="ingName" style="text-align: center;">
                            <xsl:attribute name="value">
                                <xsl:value-of select="/root/ing/name" />
                            </xsl:attribute>
                        </input>
                    </h1>
                    <p style="text-align: center;">
                        <select name="category" id="category">
                            <xsl:for-each select="/root/category">
                                <option>
                                    <xsl:attribute name="value"><xsl:value-of select="@cid" /></xsl:attribute>
                                    <xsl:if test="/root/ing/category = @cid">
                                        <xsl:attribute name="SELECTED">SELECTED</xsl:attribute>
                                    </xsl:if>
                                    <xsl:value-of select="@name" />
                                </option>
                            </xsl:for-each>
                        </select>
                        </p>
                </td>
            </tr>
            <tr>
                <td style="width:50%; text-align: center;">
                    <img><xsl:attribute name="src">/images/?image=<xsl:value-of select="/root/ing/image" /></xsl:attribute></img>
                </td>
                <td>
                <textarea name="info" id="info" style="width: 100%; height: 100%;"></textarea></td>
            </tr>
            <tr>
                <td style="vertical-align: top;">
                    Ing&#229;r i:<br />
                    <xsl:for-each select="/root/partof/drink">
                        <a><xsl:attribute name="href">/drinks/<xsl:value-of select="did" /></xsl:attribute><xsl:value-of select="name" /></a><br />
                    </xsl:for-each>
                </td>
                <td style="vertical-align: top;"></td>
            </tr>
        </tbody>
    </table>
    <button style="float: right;" type="submit">Uppdatera</button>
    </form>
    </xsl:template>
    
    
    <!-- Edit Drink -->
    <xsl:template name="editdrink">
        <form action="/edit/submitDrink/" method="post">
            <input type="hidden" name="did"><xsl:attribute name="value"><xsl:value-of select="/root/information/did" /></xsl:attribute></input>
            <table border="1" style="width: 100%">
                <tr style="height: 40px;">
                    <td style="vertical-align: top;" colspan="2">
                        <a style="float:left;"><xsl:attribute name="href">/drinks/<xsl:value-of select="/root/links/prev"/></xsl:attribute>F&ouml;reg&aring;ende</a>
                        <a style="float: right;"><xsl:attribute name="href">/drinks/<xsl:value-of select="/root/links/next"/></xsl:attribute>N&auml;sta</a>
                        <p style="width: 100%; text-align: center; display: inline;">
                            <a style="float: right; margin-right:7px;"><xsl:attribute name="href">/drinks/<xsl:value-of select="/root/links/rand"/></xsl:attribute>Slumpa</a>
                            <a style="float: right; margin-right:7px;"><xsl:attribute name="href">/drinks/<xsl:value-of select="/root/information/did"/></xsl:attribute>Visa</a>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size: 25px; text-align: center;height: 45px; vertical-align: top;">
                        <xsl:choose>
                            <xsl:when test="not(/root/information/name)">
                                <input type="text" name="name"><xsl:attribute name="value"><xsl:value-of select="/root/information/name"/></xsl:attribute></input>
                            </xsl:when>
                            <xsl:otherwise>
                                <xsl:value-of select="/root/information/name" />
                            </xsl:otherwise>
                        </xsl:choose>
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%; height: 300px; text-align: center;" rowspan="2"><xsl:element name="img"><xsl:attribute name="src">/images/?image=<xsl:value-of select="/root/information/image"/></xsl:attribute></xsl:element></td>
                    <td style="vertical-align: text-top;" colspan="2">
                        <h1 style="vertical-align: top;">Ingredienser</h1>
                        <button onclick="addIng()" type="button">En Till</button>
                        <ul id="ing">
                            <xsl:for-each select="/root/information/content/ing">
                                <li style='display: block;'>
                                    <input type='text' name='amount[]' style='width: 70px;' ><xsl:attribute name="value"><xsl:value-of select="amount" /></xsl:attribute></input>
                                    <input type='text' name='ing[]' style='width: 190px;' class="ing" ><xsl:attribute name="value"><xsl:value-of select="name"/></xsl:attribute></input>
                                    <input type='hidden' name='iid[]'><xsl:attribute name="value"><xsl:value-of select="iid"/></xsl:attribute></input>
                                </li>
                            </xsl:for-each>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top;">
                        <textarea name="instructions" style="width: 100%;" rows="10">
                            <xsl:value-of select="/root/information/instructions" />
                        </textarea>
                    </td>
                </tr>
            </table>
        <button type="submit" value="Uppdatera" style="float:right">Uppdatera</button>
        </form>
    </xsl:template>
    
    
    <!-- Edit Spirit -->
    <xsl:template name="editCategory">
    <form action="/edit/submitCategory" method="post">
    <input type="hidden" name="cid"><xsl:attribute name="value"><xsl:value-of select="/root/category/cid" /></xsl:attribute></input>

        <table border="1" style="width:100%">
            <tr>
                <td colspan="2">
                    <a style="float: right; margin-right:7px;"><xsl:attribute name="href">/category/<xsl:value-of select="/root/category/cid"/></xsl:attribute>Visa</a>
                    <h1>
                        <input type="text" name="name" id="name" style="text-align: center;"><xsl:attribute name="value"><xsl:value-of select="/root/category/name" /></xsl:attribute></input>
                    </h1>
                </td>
            </tr>
            <tr style="height: 100px;">
                <td style="width:50%; text-align: center;"><xsl:element name="img"><xsl:attribute name="src">/images/?image=<xsl:value-of select="/root/category/image"/></xsl:attribute></xsl:element></td>
                <td style="width:50%"><textarea name="info" id="info"><xsl:value-of select="/root/category/info" /></textarea></td>
            </tr>
        </table>
        <br />
        <button type="submit" value="Uppdatera" style="float:right">Uppdatera</button>
    </form>

    </xsl:template>

</xsl:stylesheet>