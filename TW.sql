DROP TABLE LOGIN;
/
DROP TABLE RAPORTVIZITE;
/
DROP TABLE CEREREVIZITE;
/
DROP TABLE DETINUTI;

/
CREATE TABLE LOGIN (USERNAME VARCHAR2(50) UNIQUE, PASSWORD VARCHAR2(50) NOT NULL);
/
CREATE TABLE DETINUTI (ID NUMBER(10) PRIMARY KEY, NUME VARCHAR2(50) NOT NULL, PRENUME VARCHAR2(50) NOT NULL, DATA_NASTERE DATE NOT NULL, DURATA_PEDEAPSA VARCHAR2(10) NOT NULL, MOTIV VARCHAR2(200) NOT NULL, SANATATE VARCHAR2(500) NOT NULL, POZA VARCHAR2(100) NOT NULL);
/
CREATE TABLE CEREREVIZITE (ID NUMBER(10) PRIMARY KEY, NUME VARCHAR2(50) NOT NULL, PRENUME VARCHAR2(50) NOT NULL, CNP NUMBER(13) UNIQUE, COD_DETINUT NUMBER(10) NOT NULL, RELATIE VARCHAR2(20) NOT NULL, NATURA_VIZITA VARCHAR2(50) NOT NULL, DATA_VIZITA DATE NOT NULL, ORA VARCHAR2(10) NOT NULL, POZA VARCHAR2(500) NOT NULL);
/
CREATE TABLE RAPORTVIZITE (ID NUMBER(10) UNIQUE NOT NULL, DURATA VARCHAR2(10) NOT NULL, OBIECTE_DATE VARCHAR2(200), OBIECTE_PRIMITE VARCHAR2(200), STARE_SPIRIT VARCHAR2(100) NOT NULL, DISCUTIE VARCHAR2(500) NOT NULL);
/
ALTER TABLE RAPORTVIZITE
  ADD CONSTRAINT vizite_id_fk FOREIGN KEY (ID) 
    REFERENCES CEREREVIZITE(ID);
ALTER TABLE CEREREVIZITE
  ADD CONSTRAINT cerere_viz_id_fk FOREIGN KEY (COD_DETINUT) 
    REFERENCES DETINUTI(ID);
/
INSERT INTO LOGIN VALUES ('GUARD263', '12345678');
INSERT INTO LOGIN VALUES ('GUARD69', '87654321');
INSERT INTO LOGIN VALUES ('DIRECTOR', 'MUHAHAHAHA');
INSERT INTO LOGIN VALUES ('admin', 'admin');
/
INSERT INTO DETINUTI VALUES (1010, 'Frunza', 'Teodor', TO_DATE('25-JAN-1996','DD-MON-YYYY'), '3 ANI', 'JAF ARMAT', 'SINDROM TOURETTE', 'poza1.jpg');
INSERT INTO DETINUTI VALUES (1011, 'Rosca', 'Alexandru', TO_DATE('23-JUL-1997','DD-MON-YYYY'), '3 ANI', 'PRADATOR SEXUAL', 'HEMOROIZI', 'poza2.jpg');
INSERT INTO DETINUTI VALUES (1012, 'Gavrilita', 'Rolland', TO_DATE('08-FEB-1996','DD-MON-YYYY'), '2 ANI', 'FURT MASINA', 'SANATOS', 'poza3.jpg');
INSERT INTO DETINUTI VALUES (1013, 'Oieru', 'Costica', TO_DATE('13-MAY-1960','DD-MON-YYYY'), '3 LUNI', 'NUDISM IN PLUBLIC', 'SANATOS', 'poza4.jpg');
INSERT INTO DETINUTI VALUES (1014, 'Oprea', 'Ionut', TO_DATE('17-APR-1960','DD-MON-YYYY'), '7 ANI', 'VIOL', 'HEPATITCA C', 'poza9.jpg');
INSERT INTO DETINUTI VALUES (1015, 'Ionescu', 'Gheorghita', TO_DATE('17-DEC-1990','DD-MON-YYYY'), '2 ANI', 'VIOLENTA DOMESTICA', 'SANATOS', 'poza6.jpg');
INSERT INTO DETINUTI VALUES (1016, 'Covrig', 'Ionel', TO_DATE('13-MAY-1977','DD-MON-YYYY'), '4 ANI', 'FURT CALIFICAT', 'TUBERCULOZA', 'poza7.jpg');
INSERT INTO DETINUTI VALUES (1017, 'Rosu', 'Cristian' , TO_DATE('27-JUN-1950','DD-MON-YYYY'), '5 ANI', 'UCIDERE DIN CULPA', 'SANATOS', 'poza8.jpg');
INSERT INTO DETINUTI VALUES (1018, 'Chioru', 'Vasile', TO_DATE('27-JUN-1992','DD-MON-YYYY'), '3 ANI', 'JAF', 'SANATOS', 'poza5.jpg');
INSERT INTO DETINUTI VALUES (1019, 'Capatos', 'Mirel', TO_DATE('25-AUG-1980','DD-MON-YYYY'), '7 ANI', 'NUDISM IN BISERICA', 'GRIPAT', 'poza10.jpg');
/
INSERT INTO CEREREVIZITE VALUES(4023,'Petrov','Andrei',1900212758135,1010,'Frate','Vizita pentru conversatie',TO_DATE('01/12/2010','MM/DD/YYYY'),'12:00','poza1.jpg');
INSERT INTO CEREREVIZITE VALUES(4024,'Cristea','Marian',1850502426875,1011,'Cunoscut','Vizita pentru afaceri',TO_DATE('08/21/2012','MM/DD/YYYY'),'15:00','poza2.jpg');
INSERT INTO CEREREVIZITE VALUES(4025,'Borcan','Cristina',2701225102368,1012,'Sotie','Vizita conjugala',TO_DATE('05/17/2013','MM/DD/YYYY'),'13:00','poza3.jpg');
INSERT INTO CEREREVIZITE VALUES(4026,'Chirvase','Ionut',1500415012587,1013,'Prieten','Vizita pentru afaceri',TO_DATE('07/22/2013','MM/DD/YYYY'),'12:00','poza4.jpg');
INSERT INTO CEREREVIZITE VALUES(4027,'Oprea','Andreea',2850622011235,1014,'Sotie','Vizita pentru actele de divort',TO_DATE('01/15/2014','MM/DD/YYYY'),'14:00','poza5.jpg');
INSERT INTO CEREREVIZITE VALUES(4028,'Dumbrava','Marius',1770103048957,1015,'Var','Vizita pentru clarificarea unor probleme',TO_DATE('09/27/2014','MM/DD/YYYY'),'12:00','poza6.jpg');
INSERT INTO CEREREVIZITE VALUES(4029,'Prastie','Maria',2630719987430,1016,'Sora','Vizita pentru conversatie',TO_DATE('12/02/2014','MM/DD/YYYY'),'15:00','poza7.jpg');
INSERT INTO CEREREVIZITE VALUES(4030,'Vasile','Ion',1680920649587,1017,'Socru','Vizita pentru conversatie',TO_DATE('05/13/2015','MM/DD/YYYY'),'14:00','poza8.jpg');
INSERT INTO CEREREVIZITE VALUES(4031,'Popescu','Marcel',1831111134698,1018,'Cunoscut','Vizita pentru incheierea unor afaceri',TO_DATE('08/08/2016','MM/DD/YYYY'),'15:00','poza9.jpg');
INSERT INTO CEREREVIZITE VALUES(4032,'Onica','Dragos',1880713153284,1019,'Amic','Vizita pentru conversatie',TO_DATE('01/24/2017','MM/DD/YYYY'),'12:00','poza10.jpg');
/

INSERT INTO RAPORTVIZITE VALUES (4023, '20 minute', '2 pachete de tigari, 2 carti, 3 reviste ',   'poza', 'fericit', 'In aceasta vizita s-a discutat despre copii detinutului si despre educatia primita acasa a acestora.');
INSERT INTO RAPORTVIZITE VALUES (4024, '30 minute', '3 kg de carne, 2 ccrembusti ',   '', 'suparat', 'In aceasta vizita s-a discutat despre durata pana la expirarea pedepsei si despre starea de spirit a penintenciarului in general.');
INSERT INTO RAPORTVIZITE VALUES (4025, '1 ora',     '4 pachete de tigari, 2 manusi, 3 prajituri ','', 'neutru', 'In aceasta vizita s-a discutat despre salteaua incomoda a detinutului, despre meciul de fotbal de aseara si despre durata pana la expirarea pedepsei.');
INSERT INTO RAPORTVIZITE VALUES (4026, '1 orea',    '2 kg de struguri, o tableta ','', 'fericit', 'In aceasta vizita s-a discutat despre mama detinutului, despre modul in care este tratat in penintenciar si despre durata pana la expirarea pedepsei.');
INSERT INTO RAPORTVIZITE VALUES (4027, '25 minute', '5 sticle de suc, 2 carti, 3 boluri ', 'felicitare', 'ofticat', 'In aceasta vizita s-a discutat despre colegul agitat al detinutului, despre rudele apropriate si despre copii detinutului');
INSERT INTO RAPORTVIZITE VALUES (4028, '32 minute', '6 pachete de tigari, 4 carti ', '', 'fericit', 'In aceasta vizita s-a discutat despre mama detinutului, despre copii detinutului si despre durata pana la expirarea pedepsei.');
INSERT INTO RAPORTVIZITE VALUES (4029, '1 ora',     '1 litru de cola, 5 carti, 3 prajituri ',  '', 'suparat', 'In aceasta vizita s-a discutat despre copii detinutului si despre mama acestora.');
INSERT INTO RAPORTVIZITE VALUES (4030, '21 minute', '2 mere, 12 carti, 2 prajituri ', '', 'depresiv', 'In aceasta vizita s-a discutat despre despre durata pana la expirarea pedepsei.');
INSERT INTO RAPORTVIZITE VALUES (4031, '32 minute', '3 pachete de tigari, 2 pixuri, 3 torturi ', '', 'nervos', 'In aceasta vizita s-a discutat despre imposibilitatea dezvoltarii personale despre nepotii detinutului impreuna cu parintii acestora si despre durata pana la expirarea pedepsei.');
INSERT INTO RAPORTVIZITE VALUES (4032, '1 ora',     '5 cutii de ananas, 2 carti, 3 placinte ',  'poza', 'confuz', 'In aceasta vizita s-a discutat despre copii detinutului si despre munca la care este supus in penintenciar.');
/
COMMIT;