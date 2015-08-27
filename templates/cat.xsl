<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template name="category">
<table border="1" style="width:100%">
    <tr>
        <td colspan="2">
            <a style="float: right; margin-right:7px;"><xsl:attribute name="href">/edit/category/<xsl:value-of select="/root/category/cid"/></xsl:attribute>Edit</a>
            <h1><xsl:value-of select="/root/category/name" /></h1>
        </td>
    </tr>
    <tr>
        <td style="width:50%; text-align: center;"><xsl:element name="img"><xsl:attribute name="src">/images/?image=<xsl:value-of select="/root/category/image"/></xsl:attribute></xsl:element></td>
        <td style="width:50%"><xsl:value-of select="/root/category/info" /></td>
    </tr>
</table>
</xsl:template>
</xsl:stylesheet>
