@ECHO off
REM Update actual project 

REM phing -verbose -debug -logfile .\updateProject.log .\updateProject.xml
REM phing -verbose -logfile .\updateProject.log .\updateProject.xml
REM phing -logfile .\updateProject.log .\updateProject.xml
phing -logfile .\updateProject.log -f .\updateProject.xml
REM phing -f .\updateProject.xml





