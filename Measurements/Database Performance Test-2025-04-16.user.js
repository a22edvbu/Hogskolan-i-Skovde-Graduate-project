// ==UserScript==
// @name         Database Performance Test
// @namespace    http://tampermonkey.net/
// @version      2025-04-16
// @description  Measurement script for measuring responsetime for webbapplikations
// @author       Edvin Bülow
// @match        http://localhost/Hogskolan-i-Skovde-Graduate-project/mysql.php
// @match        http://localhost/Hogskolan-i-Skovde-Graduate-project/mongodb.php
// @icon         https://www.google.com/s2/favicons?sz=64&domain=undefined.localhost
// @grant        none
// ==/UserScript==

let currentIteration = parseInt(localStorage.getItem('currentIteration'), 10) || 0;
let currentTermIteration = parseInt(localStorage.getItem('currentWordIteration'), 10) || 0;

let isFinal = localStorage.getItem('isFinal') === 'true';
let currentTerm = localStorage.getItem("currentWord");
let interval = 500;
let matches;
let dbName = document.querySelector(".title").innerText
let senderArr = [
    "",                                                                             // warmup search
    "phillip.allen@enron.com",
    "ina.rangel@enron.com",
    "1.11913372.-2@multexinvestornetwork.com",
    "messenger@ecm.bloomberg.com",
    "aod@newsdata.com",
    "critical.notice@enron.com",
    "market-reply@listserv.dowjones.com",
    "rebecca.cantrell@enron.com",
    "webmaster@earnings.com",
    "paul.kaufman@enron.com",
    "yild@zdemail.zdlists.com",
    "bounce-news-932653@lists.autoweb.com",
    "public.relations@enron.com",
    "stephanie.miller@enron.com",
    "tracy.arthur@enron.com",
    "sarah.novosel@enron.com",
    "bobregon@bga.com",
    "subscriptions@intelligencepress.com",
    "tim.heizenrader@enron.com",
    "rob_tom@freenet.carleton.ca",
    "calxa@aol.com",
    "ei_editor@ftenergy.com",
    "billc@greenbuilder.com",
    "frank.hayden@enron.com",
    "matt@fastpacket.net",
    "jfreeman@ssm.net",
    "owner-strawbale@crest.org",
    "kim.ward@enron.com",
    "grensheltr@aol.com",
    "yahoo-delivers@yahoo-inc.com",
    "perfmgmt@enron.com",
    "announce@inbox.nytimes.com",
    "jsmith@austintx.com",
    "alyse.herasimchuk@enron.com",
    "lisa.jacobson@enron.com",
    "christi.nicolay@enron.com",
    "richard.shapiro@enron.com",
    "gthorse@keyad.com",
    "tiffany.miller@enron.com",
    "philip.polsky@enron.com",
    "mark.whitt@enron.com",
    "arsystem@mailman.enron.com",
    "tim.belden@enron.com",
    "cbpres@austin.rr.com",
    "outlook-migration-team@enron.com",
    "pallen70@hotmail.com",
    "discount@open2win.oi3.net",
    "no.address@enron.com",
    "ray.alvarez@enron.com",
    "w..cantrell@enron.com",
    "bodyshop@enron.com",
    "mery.l.brown@accenture.com",
    "ei_editor@platts.com",
    "anchordesk_daily@anchordesk.zdlists.com",
    "itsimazing@response.etracks.com",
    "noreply@ccomad3.uu.commissioner.com",
    "ksumey@ftenergy.com",
    "edelivery@salomonsmithbarney.com",
    "showtimes@amazon.com",
    "savita.puthigai@enron.com",
    "ken.shulklapper@enron.com",
    "chad.landry@enron.com",
    "veronica.espinoza@enron.com",
    "dmallory@ftenergy.com",
    "richard.hash@openspirit.com",
    "mike.grigsby@enron.com",
    "kathie.grabstald@enron.com",
    "important_phone_call@response.etracks.com",
    "vivatrim@open2win.roi1.net",
    "clickathome@enron.com",
    "karen.buckley@enron.com",
    "bmg_support@adm.chtah.com",
    "kathryn.sheppard@enron.com",
    "james.bruce@enron.com",
    "jeff.richter@enron.com",
    "gift@amazon.com",
    "gifts@info.iwon.com",
    "keith.holst@enron.com",
    "msimpkins@winstead.com",
    "m..tholt@enron.com",
    "randy.bhatia@enron.com",
    "unsubscribe-i@networkpromotion.com",
    "e-mail.center@wsj.com",
    "ryan.o&#039;rourke@enron.com",
    "kirk.mcdaniel@enron.com",
    "jeshett@yahoo.com",
    "wise.counsel@lpl.com",
    "adrianne.engler@enron.com",
    "enron_update@concureworkplace.com",
    "book-news@amazon.com",
    "networkcommerce-tdtl20011226@ombramarketing.com",
    "info@open2win.roi1.net",
    "members@realmoney.com",
    "online.service@schwab.com",
    "morpheus@inyouremail.com",
    "capcon@gmu.edu",
    "sprint@info.iwon.com",
    "leanne@integrityrs.com",
    "postmaster@glmail2.networkpromotion.com",
    "renee.ratcliff@enron.com",
    "exclusive_ofers@sportsline.com",
    "store-news@amazon.com",
    "michelle.akers@enron.com",
    "laura.a.de.la.torre@accenture.com",
    "winnerannouncements@info.iwon.com",
    "gousa6179@hotmail.kg",
    "news@prosrm.com",
    "al.pollard@newpower.com",
    "listservices@open2win.1ll0.net",
    "infousa4492@telkom.net",
    "eservices@tdwaterhouse.com",
    "usatoday1430@hotmail.kg",
    "chairman.office@enron.com",
    "monica.l.brown@accenture.com",
    "networkcommerce-tdcd20011221@ombramarketing.com",
    "editor@hersweeps.com",
    "technology.enron@enron.com",
    "lisa@techxans.org",
    "leave-htmlnews-2508405s@lists.autoweb.com",
    "geninfo@state-bank.com",
    "davidsmith@open2win.oi3.net",
    "cpa@opthome.com",
    "lindsay.renaud@enron.com",
    "jwills3@swbell.net",
    "dleduca714@yahoo.com",
    "carole.frank@enron.com",
    "cpa@optmails.com",
    "patti.sullivan@enron.com",
    "ashley.worthing@enron.com",
    "iwon@info.iwon.com",
    "kam.keiser@enron.com",
    "d1131c2e-3c2c-40c2-bb3b-685d6a0d2700@autotoolbo",
    "rick.bellows@enron.com",
    "biliana.pehlivanova@enron.com",
    "customer_service@pmail.feer.com",
    "annualconference@prosrm.com",
    "open2win.0ll1.net@mailman.enron.com",
    "resources.human@enron.com",
    "apkpcp@prodigy.net",
    "exchange.administrator@enron.com",
    "steven.matthews@ubspainewebber.com",
    "david.oxley@enron.com",
    "customerservice@tdwaterhouse.com",
    "yevgeny.frolov@enron.com",
    "c..aucoin@enron.com",
    "software@mail01.unitedmarketingstrategies.com",
    "customerservice@chaseplatinum.po-1.com",
    "sheri.a.righi@accenture.com",
    "greg.whalley@enron.com",
    "tim.o&#039;rourke@enron.com",
    "txreport@skippingstone.com",
    "john.lavorato@enron.com",
    "stephanie.sever@enron.com",
    "cpa@oihost.net",
    "announcements.enron@enron.com",
    "promo@info.iwon.com",
    "katrina_sumey@platts.com",
    "networkcommerce-tdsd20011228@ombramarketing.com",
    "gthorse@about-cis.com",
    "deanrodgers@kaseco.com",
    "cbssportsline.com_planters@mail.0mm.com",
    "editor@theb2bvoice.com",
    "joannie.williamson@enron.com",
    "mac.d.hargrove@rssmb.com",
    "heather.dunton@enron.com",
    "prizemachine@feedback.iwon.com",
    "louise.kitchen@enron.com",
    "brad.jones@enron.com",
    "hunter.williams@grandecom.com",
    "richard.morgan@austinenergy.com",
    "david.port@enron.com",
    "delivers@amazon.com",
    "c..gossett@enron.com",
    "mondohed@gte.net",
    "steven.matthews@ubspw.com",
    "melissaspears@open2win.oi3.net",
    "software@mail02.unitedmarketingstrategies.com",
    "k..allen@enron.com",
    "pam.butler@enron.com",
    "john.arnold@enron.com",
    "colleen.koenig@enron.com",
    "jeff.leath@enron.com",
    "tracy.ramsey@enron.com",
    "jeff.youngflesh@enron.com",
    "sarah-joy.hunter@enron.com",
    "matt.harris@enron.com",
    "james.wininger@enron.com",
    "jerome_alder@dell.com",
    "karina.prizont@enron.com",
    "dorothy.woster@enron.com",
    "jennifer.stewart@enron.com",
    "trey.comiskey@enron.com",
    "bob.jordan@compaq.com",
    "gary.waxman@enron.com",
    "kim.godfrey@enron.com",
    "darin.carlisle@enron.com",
    "john.will@enron.com",
    "linda.zhou@enron.com",
    "derryl.cleaveland@enron.com",
    "brad.nebergall@enron.com",
    "marketing@sparesfinder.com",
    "bob.shults@enron.com",
    "julie.pechersky@enron.com",
    "mheffner@carrfut.com",
    "slafontaine@globalp.com",
    "kenny.soignet@enron.com",
    "matthew.arnold@enron.com",
    "klarnold@flash.net",
    "caroline.abramo@enron.com",
    "andy.zipper@enron.com",
    "russell.dyk@enron.com",
    "info@amazon.com",
    "jennifer.fraser@enron.com",
    "eleanor.fraser.2002@anderson.ucla.edu",
    "bill.berkeland@enron.com",
    "hrobertson@hbk.com",
    "lydia.cannon@enron.com",
    "scott.goodell@enron.com",
    "amy.gambill@enron.com",
    "capstone@texas.net",
    "larry.ciscon@enron.com",
    "brian.m.corbman@bofasecurities.com",
    "enron.announcements@enron.com",
    "jenwhite7@zdnetonebox.com",
    "msagel@home.com",
    "andrea.richards@enron.com",
    "peter.berzins@enron.com",
    "mariamarcelle@hotmail.com",
    "adam.r.bayer@vanderbilt.edu",
    "per.sekse@enron.com",
    "alan_batt@oxy.com",
    "fzerilli@powermerchants.com",
    "george.wasaff@enron.com",
    "fernley.dyson@enron.com",
    "brien@am.sony.com",
    "craig.brown@enron.com",
    "michael.kushner@enron.com",
    "rositza.smilenova@enron.com",
    "george.ellis@americas.bnpparibas.com",
    "ann.schmidt@enron.com",
    "christie.patrick@enron.com",
    "jim.cole@enron.com",
    "iceoperations@intcx.com",
    "margaret.allen@enron.com",
    "kristin.gandy@enron.com",
    "soblander@carrfut.com",
    "andrew.fairley@enron.com",
    "epao@mba2002.hbs.edu",
    "michael.gapinski@ubspainewebber.com",
    "steve.lafontaine@bankofamerica.com",
    "articles-email@ms1.lga2.nytimes.com",
    "thanks@amazon.com",
    "outlook.team@enron.com",
    "jpesot@gaoptions.com",
    "execed@wharton.upenn.edu",
    "david.forster@enron.com",
    "bob.lee@enron.com",
    "laura.luce@enron.com",
    "walton.agnew@enron.com",
    "jean.mrha@enron.com",
    "jim.meyer@enron.com",
    "peter.goebel@enron.com",
    "trang.dinh@enron.com",
    "don.hawkins@enron.com",
    "eric.letke@enron.com",
    "lb_electronic_orders@dell.com",
    "mark.hudgens@enron.com",
    "continental_airlines_inc@coair.rsc01.com",
    "capstone@ktc.com",
    "mike.maggi@enron.com",
    "m..schmidt@enron.com",
    "a..shankman@enron.com",
    "c10mkf@msn.com",
    "bryan.robins@enron.com",
    "40enron@enron.com",
    "5z33t95as@msn.com",
    "errol.mclaughlin@enron.com",
    "john.griffith@enron.com",
    "melissa.ginocchio@idrc.org",
    "reiscast__wave_two.um.a.1013.218@reis-reports.u",
    "dailyquote@smtp.quote.com",
    "cabramo@bloomberg.net",
    "l..nowlan@enron.com",
    "info@investments.foliofn.com",
    "joey.taylor@enron.com",
    "scott.tanner@truequote.com",
    "kathy.mayfield@enron.com",
    "daryl.dworkin@americas.bnpparibas.com",
    "webmaster@newsletter.ussoccer.com",
    "mark@capstone-ta.com",
    "info@winebid.com",
    "mailbox@mailzilla.net",
    "vance.meyer@enron.com",
    "millie.smaardyk@ourclub.com",
    "ricky.collier@enron.com",
    "johnny.palmer@enron.com",
    "jaydonahue@globalofficelink.com",
    "mfindsen@houston.rr.com",
    "knowledge@wharton.upenn.edu",
    "herthateng4882@excite.com",
    "kimberly.banner@enron.com",
    "bob.shiring@rweamericas.com",
    "jennifer.white@oceanenergy.com",
    "gcaspy@mba2002.hbs.edu",
    "ed.mcmichael@enron.com",
    "alex.hernandez@enron.com",
    "mrodriguez@nymex.com",
    "hrobertson@cloughcapital.com",
    "sarah.mulholland@enron.com",
    "ravi.thuraisingham@enron.com",
    "houston &lt;.ward@enron.com&gt;",
    "ussoccerfan@ussoccer.org",
    "robyn.zivic@enron.com",
    "felicia.solis@enron.com",
    "hotdeals@800.com",
    "402075.16792233.1@1.americanexpress.com",
    "tz3qu@msn.com",
    "invest@kg21.net",
    "citibank@info.citibankcards.com",
    "gamma@concentric.net",
    "bear@specsonline.com",
    "bob.ambrocik@enron.com",
    "n..gilbert@enron.com",
    "client@admission.com",
    "officeofthechairman2@enron.com",
    "dsanchez@tradespark.com",
    "econdev@txed.state.tx.us",
    "nmw-att1@launchfax.com",
    "swl@winelibrary.com",
    "reiscast__wave_two.um.a.1013.228@reis-reports.u",
    "newsletter@bizsites.com",
    "buy.com@enews.buy.com",
    "styarger@hotmail.com",
    "peter@libation.com",
    "jeanie.slone@enron.com",
    "courtney.votaw@enron.com",
    "wall_street_journal@xmr3.com",
    "quicken_team@email.quicken2002.com",
    "dow.jones.newswiresnewswires@dowjones.com",
    "dfeehan@apexprop.com",
    "jeff.andrews@enron.com",
    "eric.scott@enron.com",
    "kislince@er.oge.com",
    "dutch.quigley@enron.com",
    "liz.taylor@enron.com",
    "a..roberts@enron.com",
    "news@real-net.net",
    "ls2fd8x@msn.com",
    "cortwine@aol.com",
    "reminder@reply.myfamilyinc.com",
    "jeb.ligums@enron.com",
    "trytb910@msn.com",
    "f..brawner@enron.com",
    "stephen.piasio@ssmb.com",
    "ibuyit@enron.com",
    "administration.enron@enron.com",
    "feedback@intcx.com",
    "jhdiv@binswanger.com",
    "greg.piper@enron.com",
    "darren.vanek@enron.com",
    "ding.yuan@enron.com",
    "newsletter@winecommune.com",
    "reiscast__wave_two.um.a.1013.241@reis-reports.u",
    "6gc86@msn.com",
    "sally.beck@enron.com",
    "partner-news@amazon.com",
    "amy.cavazos@enron.com",
    "bill.white@enron.com",
    "postmaster@oceanenergy.com",
    "statements@investments.foliofn.com",
    "starwood@spg.0mm.com",
    "andy@spectronenergy.com",
    "407982.16792233.1@1.americanexpress.com",
    "veronica.gonzalez@enron.com",
    "infousa9596@eudoramail.com",
    "310fkn6iqva@msn.com",
    "usatoday5918@mailandnews.com",
    "united3@my.mileageplus.com",
    "lenny.hochschild@enron.com",
    "vbz8g5@msn.com",
    "messenger@directtrak.com",
    "news@genealogy.com",
    "carrfuturesenergy@carrfut.com",
    "wtashnek@aol.com",
    "rwolkwitz@powermerchants.com",
    "travelercare@orbitz.com",
    "site59@site59.rsc03.com",
    "karen@mpenner.com",
    "j9aqqi2@msn.com",
    "amerosie748@yahoo.com",
    "winex@cartsonline.com",
    "jpetriello@prebon.com",
    "troy.black@enron.com",
    "electronic_ideas@800.com",
    "keith.robinson@ourclub.com",
    "reiscast__wave_two.um.a.1013.332@reis-reports.u",
    "firstdata9857@gmx.co.uk",
    "universalcardservices@universalcard.m0.net",
    "kward1@houston.rr.com",
    "artistinsider@info.artistdirect.com",
    "music@800.com",
    "2p5wd2@msn.com",
    "buckner.thomas@enron.com",
    "jeff.huff@enron.com",
    "edmundg@manfinancial.com",
    "john.coyle@enron.com",
    "my-login-request@yahoo-inc.com",
    "aclark@firstcallassociates.com",
    "jennifer.denny@enron.com",
    "jfinkle@iedconline.org",
    "midnitemail@lists.em5000.com",
    "conferences@hedgefund.net",
    "quicken@update.quicken.com",
    "specials@genealogy.com",
    "nyadmin@intcx.com",
    "reiscast__wave_two.um.a.1013.349@reis-reports.u",
    "chefscatalog_support@chefscatalog.chtah.com",
    "rshiring@pcenergy.com",
    "lydia.delgado@enron.com",
    "dudley.poston@williams.com",
    "john.cummings@enron.com",
    "danaggie@hotmail.com",
    "steve.c.lengkeekjr@conectiv.com",
    "r..harrington@enron.com",
    "bcollins@nymex.com",
    "r..shepperd@enron.com",
    "tanya.rohauer@enron.com",
    "lara.leibman@enron.com",
    "sarah.wesner-soong@enron.com",
    "christopher.jeska@enron.com",
    "kbusch@energyargus.com",
    "update@briefing.com",
    "greg.woulfe@enron.com",
    "creditprocessing@buy.com",
    "iris.mack@enron.com",
    "dmcelduff@nymex.com",
    "jonathan.whitehead@enron.com",
    "s..shively@enron.com",
    "denver.plachy@enron.com",
    "david.deveny@bmo.com",
    "credit &lt;.williams@enron.com&gt;",
    "ltaylor@heidrick.com",
    "rick.wurlitzer@enron.com",
    "michael.gapinski@ubspw.com",
    "anthony.dayao@enron.com",
    "keith.karako@citi.com",
    "sales@popswine.com",
    "editor@casinosweeps.com",
    "amore889@yahoo.com",
    "customerservice@qwikfliks.com",
    "melissa.dozier@enron.com",
    "suresh.raghavan@enron.com",
    "amy.spoede@enron.com",
    "nicholas.m.sopkin@db.com",
    "jeff.bartlett@enron.com",
    "donald.herrick@enron.com",
    "debi.vanwey@enron.com",
    "brad.blesie@enron.com",
    "barbara.lewis@enron.com",
    "harry.arora@enron.com",
    "eileen.buerkert@enron.com",
    "advapl@vsnl.com",
    "ross.mesquita@enron.com",
    "erequest@enron.com",
    "office.chairman@enron.com",
    "tobias.munk@enron.com",
    "marc.eichmann@enron.com",
    "scano@velaw.com",
    "raj.thapar@enron.com",
    "dave.samuels@enron.com",
    "john.spitz@enron.com",
    "maksym.yegorychev@owen2002.vanderbilt.edu",
    "muthukumar.krishnan@owen2002.vanderbilt.edu",
    "vic.gatto@owen2002.vanderbilt.edu",
    "jens.gobel@enron.com",
    "rahil.jafry@enron.com",
    "ajay.jagsi@owen2002.vanderbilt.edu",
    "lee.jackson@enron.com",
    "libasco@netvigator.com",
    "john.wack@enron.com",
    "dan.bruce@enron.com",
    "vk167@hotmail.com",
    "dmitri.villevald@owen2002.vanderbilt.edu",
    "paula.hix@enron.com",
    "nmwolf@duke-energy.com",
    "kenneth.d&#039;silva@enron.com",
    "kristi.demaiolo@enron.com",
    "wsandberg3@attbi.com",
    "anne.burch@currenex.com",
    "j24707-r5689@iqmailer.net",
    "djtheroux@independent.org"
];

//const maxIterations = 3;
const maxIterations = senderArr.length;
const maxTermIterations = 3;

// Ensure the script runs after the page is fully loaded
if (document.readyState === 'complete') {
    init();
} else {
    window.addEventListener('load', init);
}

function init() {
    console.log("Script initialized and running");

    function getData() {
        var str = localStorage.getItem("theData");
        dbName = document.querySelector(".title").innerHTML;

        // Create anchor and click it to download data
        var anchor = document.createElement("a");
        anchor.setAttribute("href", encodeURI(str));
        anchor.setAttribute("download", "mdbFilteredBrowser8000Data.csv");
        anchor.innerHTML = "Click Here to download";
        document.body.appendChild(anchor);
        anchor.click();
        localStorage.removeItem("theData");
    }

    if (localStorage.getItem('returnBack') === 'true') {
    localStorage.removeItem('returnBack');

    // Check if it's the final return after the last click
    if (isFinal) {
        var measurement = new Date().valueOf();
        var oldTime = parseInt(localStorage.getItem("Oldval"));
        var measuredLoadTime = measurement - oldTime;
        var str = localStorage.getItem("theData");
        str += measurement + "," + oldTime + "," + (measuredLoadTime) + "," + currentTerm + "\n";
        localStorage.setItem("theData", str);

        // Clean up and download
        getData();
        localStorage.removeItem("Counter");
        localStorage.removeItem("currentIteration");
        localStorage.removeItem("currentTermIteration");
        localStorage.removeItem("currentTerm");
        localStorage.removeItem("isFinal");

        alert("Script Finalized! 2");
    } else {
        window.history.back();
    }
    return;
}


    setTimeout(function() {
        //console.log(senderArr);
        console.log(currentTermIteration);

        let submitBtn = document.querySelector(".submitBtn");
        let queryAllBtn = document.querySelector(".defaultRadio");
        let selectBtn = document.querySelector(".selectRadio");
        const mailFromTextbox = document.querySelector(".fromInput");

        if (currentTermIteration >= maxTermIterations) {
            currentTermIteration = 0;
            currentIteration++;
            localStorage.setItem('currentIteration', currentIteration);
        }

        currentTerm = senderArr[currentIteration] || "";
        localStorage.setItem('currentTerm', currentTerm);
        const searchTerm = currentTerm;

        if (currentIteration < maxIterations) {
            if (submitBtn) {

                var measurement = new Date().valueOf();

                mailFromTextbox.value = searchTerm;
                // queryAllBtn.click();
                selectBtn.click();

                let cnt = parseInt(localStorage.getItem("Counter"));

                localStorage.setItem('returnBack', 'true');

                // If cnt is Null then change it to 0
                if (isNaN(cnt)) cnt = 0;

                    // If its the first iteration, save word type, distribution type and browser name
                    if (cnt == 0) {
                        str = "data:text/csv;charset=utf-8,";
                        // dbName = document.querySelector(".title").innerHTML;
                        // str +=  "Database: " + dbName + "\n";
                        localStorage.setItem("Oldval", measurement);
                    } else {
                        // logs the searchtime
                        var oldTime = parseInt(localStorage.getItem("Oldval"));
                        localStorage.setItem("Oldval", measurement);
                        var measuredLoadTime = measurement - oldTime;
                        var str = localStorage.getItem("theData");

                        str += measurement + "," + oldTime + "," + (measuredLoadTime - interval) + "," + currentTerm + "\n";
                }
                cnt++;
                localStorage.setItem("Counter", cnt);
                localStorage.setItem("theData", str);

                currentTermIteration++;
                localStorage.setItem('currentWordIteration', currentTermIteration);

                if (currentIteration == maxIterations) {
                    localStorage.setItem('isFinal', 'true');
                }
                submitBtn.click();
            }
        } else {
            // Final logging and download

            getData();
        }
    }, interval);
}
