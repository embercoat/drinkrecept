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
<xsl:template name="drink">
    <table border="1" style="width: 100%">
        <tr style="height: 40px;">
            <td style="vertical-align: top;" colspan="2">
                <a style="float:left;"><xsl:attribute name="href">/drinks/<xsl:value-of select="/root/links/prev"/></xsl:attribute>F&ouml;reg&aring;ende</a>
                <a style="float: right;"><xsl:attribute name="href">/drinks/<xsl:value-of select="/root/links/next"/></xsl:attribute>N&auml;sta</a>
                <p style="width: 100%; text-align: center; display: inline;">
                    <a style="float: right; margin-right:7px;"><xsl:attribute name="href">/drinks/<xsl:value-of select="/root/links/rand"/></xsl:attribute>Slumpa</a>
                    <a style="float: right; margin-right:7px;"><xsl:attribute name="href">/edit/drink/<xsl:value-of select="/root/information/did"/></xsl:attribute>Edit</a>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h1><xsl:value-of select="/root/information/name"/></h1>
                <xsl:if test="/root/notfound">
                    <div class="missing_ings">Följande saknas för att kunna blanda<br />
                    <xsl:for-each select="/root/notfound/ing">
                        <a><xsl:attribute name="href">/ing/<xsl:value-of select="iid" /></xsl:attribute><xsl:value-of select="name" /></a>&nbsp;
                    </xsl:for-each>
                    </div>
                </xsl:if>
            </td>
        </tr>
        <tr>
            <td style="width: 50%; height: 300px; text-align: center;" rowspan="2"><xsl:element name="img"><xsl:attribute name="src">/images/?image=<xsl:value-of select="/root/information/image"/></xsl:attribute></xsl:element></td>
            <td style="vertical-align: text-top;" colspan="2">
                <h1 style="vertical-align: top;">Ingredienser</h1>
                <ul>
                    <xsl:for-each select="/root/information/content/ing">
                        <li style='display: block;'><xsl:if test="amount"><xsl:value-of select="amount" />&nbsp;</xsl:if><xsl:element name="a"><xsl:attribute name="href">/ing/<xsl:value-of select="iid" /></xsl:attribute><xsl:value-of select="name"/></xsl:element></li>
                    </xsl:for-each>
                </ul>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top;">
                <xsl:value-of select="/root/information/instructions" />
            </td>
        </tr>
    </table>
</xsl:template>
</xsl:stylesheet>