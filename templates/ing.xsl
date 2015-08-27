<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template name="ing">
<table border="1" style="width:100%;">
    <tbody>
        <tr>
            <td colspan="2">
                <a style="float: left;">
                    <xsl:attribute name="href">
                        /backend/adding/<xsl:value-of select="/root/ing/iid" />
                    </xsl:attribute>[Add]
                </a>
                <a style="float: right;">
                    <xsl:attribute name="href">
                        /edit/ing/<xsl:value-of select="/root/ing/iid" />
                    </xsl:attribute>[Edit]
                </a>
                <h1 style="text-align: center; font-size: 25px;text-decoration: none;">
                    <xsl:value-of select="/root/ing/name" />
                </h1>
                <p style="text-align: center;"><xsl:value-of select="/root/ing/category" /></p>
            </td>
        </tr>
        <tr>
            <td style="width:50%; text-align: center;">
                <img><xsl:attribute name="src">/images/?image=<xsl:value-of select="/root/ing/image" /></xsl:attribute></img>
            </td>
            <td>Information:<br />
            <xsl:value-of select="/root/ing/info" /></td>
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
    
            

</xsl:template>
</xsl:stylesheet>
