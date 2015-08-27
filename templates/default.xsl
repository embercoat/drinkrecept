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
        <xsl:include href="drink.xsl" />
        <xsl:include href="list.xsl" />
        <xsl:include href="ing.xsl" />
        <xsl:include href="mydrinks.xsl" />
        <xsl:include href="bars.xsl" />
        <xsl:include href="savebar.xsl" />
        <xsl:include href="edit.xsl" />
        <xsl:include href="cat.xsl" />

<xsl:output method="html" encoding="UTF-8" indent="yes" standalone="yes" />
<xsl:template match="/">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <xsl:choose>
            <xsl:when test="/root/template = 'drink'">
                <title>Drinkrecept - <xsl:value-of select="/root/information/name" /></title>
            </xsl:when>
            <xsl:otherwise>
                <title>Drinkrecept</title>
            </xsl:otherwise>
        </xsl:choose>
        <script type="text/javascript" src="http://zeus.scripter.se/jquery.js">1;</script>
        <script type="text/javascript" src="/js/bars.js">1;</script>
        <script type="text/javascript" src="/js/welcome.js">1;</script>
        <link rel="stylesheet" type="text/css" href="/css/reset.css" />
        <link rel="stylesheet" type="text/css" href="/css/style.css" />
        <script type="text/javascript" src="/js/edit.js">1;</script>
        <script type="text/javascript" src="/js/jquery.autocomplete.js">1;</script>
        <style type="text/css">
            * {
                padding: 0px;
                margin: 0px;
                font-family: Cambria;
                font-size: 16px;
            }
            body {
                padding: 8px;
                margin: 8px;
            }
            ul{
                list-style: none;
            }
        </style>
    </head>
    <body onclick="closeLogin()">
        <table border="1" style="width: 1000px; position: absolute; left: 50%; top: 8%; margin-left: -500px;">
            <tr>
                <td colspan="2">
                    <ul style="display: inline;padding: 0px">
                        <li style="display: inline;"><a href="/drinks/">Drinkar</a>&nbsp;</li>
                        <li style="display: inline;"><a href="/drinklist/">Drinklista</a>&nbsp;</li>
                        <li style="display: inline;"><a href="/ing/">Ingredienser</a>&nbsp;</li>
                        <li style="display: inline;"><a href="/mydrinks/">Mina Drinkar</a>&nbsp;</li>
                        <li style="display: inline;"><a href="/edit/drink/new/">Ny Drink</a>&nbsp;</li>
                        <li style="display: inline;"><a href="/bars/">Barer</a>&nbsp;</li>
                        <li style="display: inline;"><a href="/category/">Kategorier</a>&nbsp;</li>
                        <li style="display: inline;"><a href="/edit/category/new/">Ny Kategori</a>&nbsp;</li>
                    </ul>
                    <ul class="topright">
                        <li><a href="javascript:loginPopup()">Logga In</a></li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td style="width: 200px; vertical-align: top; padding: 0px; margin: 0px;">
                <script type="text/javascript" src="/js/sidebar.js">alert()</script>
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <td style="width: 100%;">Ditt Barsk&aring;p</td>
                                <td>&nbsp;</td>
                            </tr>
                        </thead>
                        <tbody>
                            <xsl:for-each select="/root/barcabinet/ing">
                                <xsl:sort select="name" />
                                <tr><td><a class="ing"><xsl:attribute name="href">/ing/<xsl:value-of select="@iid" /></xsl:attribute><xsl:value-of select="@name" /></a></td><td><a class=""><xsl:attribute name="href">/backend/deling/<xsl:value-of select="@iid" />/</xsl:attribute>X</a></td></tr>
                            </xsl:for-each>
                        </tbody>
                    </table>
                </td>
                <td style="vertical-align: top;">
                    <xsl:if test="/root/messages">
                        <ul>
                        <xsl:if test="/root/messages/success">
                            <xsl:for-each select="/root/messages/success/message">
                                <li class="success"><xsl:value-of select="." /></li>
                            </xsl:for-each>
                        </xsl:if>
                        <xsl:if test="/root/messages/fail">
                            <xsl:for-each select="/root/messages/fail/message">
                                <li class="fail"><xsl:value-of select="." /></li>
                            </xsl:for-each>
                        </xsl:if>
                        </ul>
                    </xsl:if>
                    <xsl:call-template>
                    	<xsl:attribute name="name"><xsl:value-of select="/root/template" /></xsl:attribute>
                    </xsl:call-template>

                </td>
            </tr>
        </table>
        <!--a style="margin-right:7px;" href="submit.edit.logic.php?delete=<?=$_GET['did']; ?>">Ta bort</a-->
        <div id="updatePopup">
            <form id="updateform" method="post">
                <table border="1">
                    <tr>
                        <td colspan="2">Baren är lösenordsskyddad. Ange lösenord för att ändra baren.</td>
                    </tr>
                    <tr>
                        <td><label for="password">Lösenord</label></td>
                        <td><input id="password" type="password" name="password" /></td>
                    </tr>
                    <tr>
                        <td><input type="submit" value="Uppdatera" /></td>
                    </tr>
                </table>
            </form>
        </div>
        <div id="loginPopup">
            <form id="loginform" method="post" action="/login/login">
                <table border="0" id="loginTable">
                    <tr>
                        <td><label for="loginUsername">U</label></td>
                        <td><input id="loginUsername" type="text" name="loginUsername" /></td>
                    </tr>
                    <tr>
                        <td><label for="loginPassword">P</label></td>
                        <td><input id="loginPassword" type="password" name="loginPassword" /></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right;"><input type="submit" value="Logga In" /></td>
                    </tr>
                </table>
            </form>
        </div>
    </body>
</html>
</xsl:template>
</xsl:stylesheet>
