url1 = "http://8.8.8.8:90/login" 
Call Main 
Sub Main() 
'	username=""                  '用户名
'	password=""        '密码
'	passwordstr="adminlirui123"        '密码
	postdata="uri=OC44LjguODo5MC9sb2dpbg%3D%3D&terminal=pc&login_type=login&check_passwd=0&show_tip=block&show_change_password=block&short_message=none&show_captcha=none&show_read=block&show_assure=none&username=lirui5%40leju.com&assure_phone=&password1=adminlirui123&password=%25B3%2512%2581%25A0G%2506%25A5%257C%2593%25AA%25B3r%25F5&new_password=&retype_newpassword=&captcha_value=&save_user=1&save_pass=1&read=1"
	respStr = PostHttp(url1,postdata) 
'	WScript.Echo(respStr) 
	'MsgBox respStr
	'执行get
'	set re = new RegExp
'	re.Pattern ="(""(http\:\/\/.+)"")"
'	re.Global = True
'	re.IgnoreCase = True
	're.test(respStr)
'	Set Matches = re.Execute(respStr) 
'	url2 = Matches(0).Value
	'MsgBox url2
    'Call GetHttp(url2) 
    'Call WriteFileUTF8("c:\utf8.txt", "UTF8字符串") 
End Sub 
 
''''''''''''''''''''''''以下是函数定义'''''''''''''''''''''''''''''''''''''' 
 
' Valid Charset values for ADODB.Stream 
Const CdoBIG5        = "big5" 
Const CdoEUC_JP      = "euc-jp" 
Const CdoEUC_KR      = "euc-kr" 
Const CdoGB2312      = "gb2312" 
Const CdoISO_2022_JP = "iso-2022-jp" 
Const CdoISO_2022_KR = "iso-2022-kr" 
Const CdoISO_8859_1  = "iso-8859-1" 
Const CdoISO_8859_2  = "iso-8859-2" 
Const CdoISO_8859_3  = "iso-8859-3" 
Const CdoISO_8859_4  = "iso-8859-4" 
Const CdoISO_8859_5  = "iso-8859-5" 
Const CdoISO_8859_6  = "iso-8859-6" 
Const CdoISO_8859_7  = "iso-8859-7" 
Const CdoISO_8859_8  = "iso-8859-8" 
Const CdoISO_8859_9  = "iso-8859-9" 
Const cdoKOI8_R      = "koi8-r" 
Const cdoShift_JIS   = "shift-jis" 
Const CdoUS_ASCII    = "us-ascii" 
Const CdoUTF_7       = "utf-7" 
Const CdoUTF_8       = "utf-8" 
 
' ADODB.Stream file I/O constants 
Const adTypeBinary          = 1 
Const adTypeText            = 2 
Const adSaveCreateNotExist  = 1 
Const adSaveCreateOverWrite = 2 
 
Function GetHttp(url) 
    Set xmlhttp = CreateObject("Msxml2.ServerXMLHTTP")  
    'postdata = "" 
    xmlhttp.Open "GET", url, False 
    xmlhttp.setRequestHeader "Authorization", "Basic " & Base64encode("test:pass") 
    'xmlhttp.setRequestHeader("Referer","来路的绝对地址") 
    'xmlhttp.setRequestHeader "Cookie",Cookies   'Cookie 
    xmlhttp.Send postdata 
    'Wscript.echo xmlhttp.status & ":" & xmlhttp.statusText 
    respStr = BytesToBstr(xmlhttp.responseBody, "UTF-8") 
    Wscript.echo respStr 
    Set xmlhttp = nothing 
End Function 
 
Function PostHttp(url,postdata) 
    Set xmlhttp = CreateObject("Msxml2.ServerXMLHTTP")  
    'Wscript.echo postdata 
    xmlhttp.Open "POST", url1, False 
    xmlhttp.setRequestHeader "CONTENT-TYPE","application/x-www-form-urlencoded" 
    xmlhttp.setRequestHeader "Authorization", "Basic " & Base64encode("test:pass") 
    'xmlhttp.setRequestHeader("Referer","来路的绝对地址") 
    'xmlhttp.setRequestHeader "Cookie",Cookies   'Cookie 
    xmlhttp.Send postdata 
    'Wscript.echo xmlhttp.status & ":" & xmlhttp.statusText 
    respStr = BytesToBstr(xmlhttp.responseBody, "GB2312") 
    PostHttp = respStr
    'Wscript.echo respStr 
    set xmlhttp = nothing 
End Function 
 
Function Str2Bytes(str,charset) 
    Dim ms,strRet 
    Set ms = CreateObject("ADODB.Stream")    '建立流对象 
        ms.Type = 2             ' Text 
        ms.Charset = charset    '设置流对象的编码方式为 charset 
        ms.Open                     
        ms.WriteText str            '把str写入流对象中         
        ms.Position = 0         '设置流对象的起始位置是0 以设置Charset属性 
        ms.Type = 1              'Binary 
        vout = ms.Read(ms.Size)    '取字符流 
        ms.close                '关闭流对象 
    Set ms = nothing 
    Str2Bytes = vout 
End Function 
 
Function BytesToBstr(strBody,CodeBase) 
    If lenb(strBody) = 0 Then  
        BytesToBstr = "" 
        Exit Function 
    End If 
    dim objStream 
    set objStream = CreateObject("Adodb.Stream") 
    objStream.Type = 1 
    objStream.Mode =3 
    objStream.Open 
    objStream.Write strBody 
    objStream.Position = 0 
    objStream.Type = 2 
    objStream.Charset = CodeBase 
    BytesToBstr = objStream.ReadText 
    objStream.Close 
    set objStream = nothing 
End Function 
 
Function URLEncoding(vstrIn) 
    strReturn = "" 
    For i = 1 To Len(vstrIn) 
        ThisChr = Mid(vStrIn,i,1) 
        If Abs(Asc(ThisChr)) < &HFF Then 
            strReturn = strReturn & ThisChr 
        Else 
            innerCode = Asc(ThisChr) 
            If innerCode < 0 Then 
                innerCode = innerCode + &H10000 
            End If 
            Hight8 = (innerCode And &HFF00) OR &HFF 
            Low8 = innerCode And &HFF 
            strReturn = strReturn & "%" & Hex(Hight8) &  "%" & Hex(Low8) 
        End If 
    Next 
    URLEncoding = strReturn 
End Function 
 
Const sBASE_64_CHARACTERS = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/"  
 
Function Base64encode(ByVal asContents)  
    Dim lnPosition  
    Dim lsResult  
    Dim Char1  
    Dim Char2  
    Dim Char3  
    Dim Char4  
    Dim Byte1  
    Dim Byte2  
    Dim Byte3  
    Dim SaveBits1  
    Dim SaveBits2  
    Dim lsGroupBinary  
    Dim lsGroup64  
 
    If Len(asContents) Mod 3 > 0 Then asContents = asContents & String(3 - (Len(asContents) Mod 3), " ")  
    lsResult = ""  
 
    For lnPosition = 1 To Len(asContents) Step 3  
    lsGroup64 = ""  
    lsGroupBinary = Mid(asContents, lnPosition, 3)  
 
    Byte1 = Asc(Mid(lsGroupBinary, 1, 1)): SaveBits1 = Byte1 And 3  
    Byte2 = Asc(Mid(lsGroupBinary, 2, 1)): SaveBits2 = Byte2 And 15  
    Byte3 = Asc(Mid(lsGroupBinary, 3, 1))  
 
    Char1 = Mid(sBASE_64_CHARACTERS, ((Byte1 And 252) \ 4) + 1, 1)  
    Char2 = Mid(sBASE_64_CHARACTERS, (((Byte2 And 240) \ 16) Or (SaveBits1 * 16) And &HFF) + 1, 1)  
    Char3 = Mid(sBASE_64_CHARACTERS, (((Byte3 And 192) \ 64) Or (SaveBits2 * 4) And &HFF) + 1, 1)  
    Char4 = Mid(sBASE_64_CHARACTERS, (Byte3 And 63) + 1, 1)  
    lsGroup64 = Char1 & Char2 & Char3 & Char4  
 
    lsResult = lsResult + lsGroup64  
    Next  
 
    Base64encode = lsResult  
End Function 
 
Function Base64decode(ByVal asContents) 
    Dim iDataLength, sOutputString, iGroupInitialCharacter 
    asContents = Replace(Replace(Replace(asContents, vbCrLf, ""), vbTab, ""), " ", "") 
    iDataLength = Len(asContents) 
    If iDataLength Mod 4 <> 0 Then 
    Base64Decode = "错误格式" 
    Exit Function 
    End If 
    For iGroupInitialCharacter = 1 To iDataLength Step 4 
    Dim iDataByteCount, iCharacterCounter, sCharacter, iData, iGroup, sPreliminaryOutString 
    iDataByteCount = 3 
    iGroup = 0 
    For iCharacterCounter = 0 To 3 
    sCharacter = Mid(asContents, iGroupInitialCharacter + iCharacterCounter, 1) 
    If sCharacter = "=" Then 
    iDataByteCount = iDataByteCount - 1 
    iData = 0 
    Else 
    iData = InStr(1, sBASE_64_CHARACTERS, sCharacter, 0) - 1 
    If iData = -1 Then 
    Base64Decode = "错误格式" 
    Exit Function 
    End If 
    End If 
    iGroup = 64 * iGroup + iData 
    Next 
    iGroup = Hex(iGroup) 
    iGroup = String(6 - Len(iGroup), "0") & iGroup 
    sPreliminaryOutString = Chr(CByte("&H" & Mid(iGroup, 1, 2))) & Chr(CByte("&H" & Mid(iGroup, 3, 2))) & Chr(CByte("&H" & Mid(iGroup, 5, 2))) 
    sOutputString = sOutputString & Left(sPreliminaryOutString, iDataByteCount) 
    Next 
    Base64Decode = sOutputString 
End Function 
 
Function ReadBinary(FileName) 
  Dim Buf(), I 
  With CreateObject("ADODB.Stream") 
    .Mode = 3 
    .Type = 1 
    .Open 
    .LoadFromFile FileName 
    ReDim Buf(.Size - 1) 
    For I = 0 To .Size - 1: Buf(I) = AscB(.Read(1)): Next 
    .Close 
  End With 
  ReadBinary = Buf 
End Function 
 
Sub WriteBinary(FileName, Buf) 
  Dim I, aBuf, Size, bStream 
  Size = UBound(Buf): ReDim aBuf(Size \ 2) 
  For I = 0 To Size - 1 Step 2 
      aBuf(I \ 2) = ChrW(Buf(I + 1) * 256 + Buf(I)) 
  Next 
  If I = Size Then aBuf(I \ 2) = ChrW(Buf(I)) 
  aBuf=Join(aBuf, "") 
  Set bStream = CreateObject("ADODB.Stream") 
  bStream.Type = 1 
  bStream.Open 
  With CreateObject("ADODB.Stream") 
    .Type = 2  
    .Open 
    .WriteText aBuf 
    .Position = 2 
    .CopyTo bStream 
    .Close 
  End With 
  bStream.SaveToFile FileName, 2: bStream.Close 
  Set bStream = Nothing 
End Sub 
 
Function WriteFileUTF8(FileName, str) 
    dim adodbStream 
    Set adodbStream = CreateObject("ADODB.Stream") 
    adodbStream.Type = adTypeText 
    adodbStream.Open 
    adodbStream.Charset = CdoUTF_8 
    adodbStream.WriteText(str) 
    adodbStream.SaveToFile FileName & flnm, 2 
    adodbStream.Close() 
    set adodbStream = nothing 
End Function 
 
Function UTF8( myFileIn, myFileOut )  
    Dim objStream 

    On Error Resume Next 
     
    Set objStream = CreateObject( "ADODB.Stream" ) 
    objStream.Open 
    objStream.Type = adTypeText 
    objStream.Position = 0 
    objStream.Charset = CdoUTF_8 
    objStream.LoadFromFile myFileIn 
    objStream.SaveToFile myFileOut, adSaveCreateOverWrite 
    objStream.Close 
    Set objStream = Nothing 
     
    If Err Then 
        UTF8 = False 
    Else 
        UTF8 = True 
    End If 
     
    On Error Goto 0 
End Function 