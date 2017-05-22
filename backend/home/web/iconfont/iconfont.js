;(function(window) {

  var svgSprite = '<svg>' +
    '' +
    '<symbol id="icon-fangdajing" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M850.453 800.653l0.105-0.223-197.89-193.493c38.961-45.944 61.363-103.043 63.198-161.203 3.664-70.834-24.038-144.003-74.073-195.682-42.739-45.105-102.838-75.421-164.821-83.209-12.031-1.639-24.352-2.458-36.625-2.458-61.344 0-121.126 19.942-168.322 56.113-54.705 40.781-92.468 101.996-103.616 167.925-11.054 61.446 0.911 127.372 32.811 180.82 21.819 37.152 52.888 69.073 89.828 92.298 33.909 21.453 72.924 35.474 112.775 40.485 11.958 1.62 24.219 2.439 36.445 2.439 56.586 0 112.572-17.3 158.132-48.773l197.642 193.343 3.655 3.368 0.105-0.091c6.515 5.235 14.768 8.14 23.319 8.14 20.202 0 36.637-16.127 36.637-35.947 0-8.787-3.358-17.279-9.308-23.853M649.856 536.743c-32.378 64.734-97.46 112.073-169.899 123.548-33.909 5.773-69.646 3.986-102.941-5.139-64.972-17.433-120.583-63.58-148.808-123.528-32.677-66.559-28.602-150.391 10.387-213.6 34.702-58.404 95.873-99.427 163.56-109.707l4.802-0.71c2.525-0.409 5.069-0.799 7.555-1.082 8.369-0.858 16.882-1.286 25.282-1.286 34.569 0 68.716 7.283 98.811 21.085 55.059 24.691 98.955 70.579 120.409 125.929 23.529 59.109 20.125 128.061-9.157 184.488z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-jiantou" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M362.484736 186.295296 515.892224 186.295296 762.805248 504.431616 508.982272 814.168064 362.704896 814.168064 612.736 504.431616Z"  ></path>' +
    '' +
    '<path d="M610.075648 795.736064"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-ttpodicon" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M105.950408 956.603007 105.950408 61.999053l762.893488 442.153212L105.950408 956.603007zM149.643323 138.231271l0 741.247194 632.10128-374.892319L149.643323 138.231271z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-youjiantou" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M679.374008 511.753383 280.140305 112.531959c-11.102872-11.090593-11.102872-29.109991 0-40.177048 11.090593-11.109012 29.092595-11.109012 40.188304 0l414.455383 414.450267c2.229784 1.246387 4.973268 0.947582 6.874571 2.843768 6.076392 6.076392 8.508791 14.167674 7.936763 22.103414 0.572028 7.941879-1.860371 16.034185-7.936763 22.097274-1.902326 1.908466-4.650927 1.603521-6.886851 2.856048L320.329633 951.169251c-11.096732 11.084453-29.097712 11.084453-40.188304 0-11.102872-11.114129-11.102872-29.091572 0-40.200584L679.374008 511.753383z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-zuojiantou" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M342.117868 511.753383l399.23268-399.221424c11.102872-11.090593 11.102872-29.109991 0-40.177048-11.090593-11.109012-29.092595-11.109012-40.188304 0L286.705837 486.805178c-2.229784 1.246387-4.973268 0.947582-6.874571 2.843768-6.076392 6.076392-8.508791 14.167674-7.936763 22.103414-0.572028 7.941879 1.860371 16.034185 7.936763 22.097274 1.902326 1.908466 4.650927 1.603521 6.886851 2.856048l414.444127 414.461523c11.096732 11.084453 29.097712 11.084453 40.188304 0 11.102872-11.114129 11.102872-29.091572 0-40.200584L342.117868 511.753383z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-shenjingxitong" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M955.665067 293.317973c-50.26816-186.73664-193.795413-310.439253-320.669013-276.261547-38.90176 10.461867-71.5264 34.80576-96.89088 68.570453-0.12288 0-0.28672 0-0.4096-0.054613-2.17088 8.850773-12.8 25.678507-27.388587 25.678507-14.585173 0-21.63712-15.899307-27.374933-25.678507-0.136533 0.054613-0.28672 0.054613-0.4096 0.054613-25.367893-33.768107-57.98912-58.108587-96.904533-68.570453C258.75456-17.12128 115.217067 106.581333 64.96256 293.317973c-50.2272 186.6752 11.912533 365.677227 138.77248 399.827627 7.1168 1.91488 14.27456 3.170987 21.435733 4.072107-0.28672 3.577173-0.628053 7.12704-0.628053 10.707627 0 82.91328 83.749547 150.125227 187.11552 150.125227 23.688533 0 46.250667-3.689813 67.140267-10.110293l0 166.08256 6.939307 0 49.165653 0 6.925653 0L541.82912 847.940267c20.85888 6.42048 43.451733 10.110293 67.15392 10.110293 103.35232 0 187.11552-67.208533 187.11552-150.125227 0-3.580587-0.341333-7.130453-0.628053-10.707627 7.144107-0.904533 14.301867-2.16064 21.418667-4.072107C943.752533 658.9952 1005.892267 479.996587 955.665067 293.317973z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-weixin" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M503.04 408.32c17.92 0 30.72-12.8 30.72-30.72 0-17.92-11.52-30.72-30.72-30.72-17.92 0-37.12 11.52-37.12 30.72C465.92 395.52 483.84 408.32 503.04 408.32z"  ></path>' +
    '' +
    '<path d="M582.4 524.8c-11.52 0-24.32 12.8-24.32 24.32 0 12.8 12.8 24.32 24.32 24.32 19.2 0 30.72-11.52 30.72-24.32C613.12 537.6 600.32 524.8 582.4 524.8z"  ></path>' +
    '' +
    '<path d="M512 0C229.12 0 0 229.12 0 512c0 282.88 229.12 512 512 512s512-229.12 512-512C1024 229.12 794.88 0 512 0zM410.88 659.2c-30.72 0-55.04-6.4-85.76-12.8l-85.76 42.24 24.32-72.96c-61.44-42.24-97.28-98.56-97.28-165.12C166.4 335.36 276.48 243.2 410.88 243.2c120.32 0 225.28 72.96 247.04 171.52-7.68-1.28-15.36-1.28-23.04-1.28-116.48 0-207.36 87.04-207.36 193.28 0 17.92 2.56 34.56 7.68 51.2C426.24 659.2 418.56 659.2 410.88 659.2zM771.84 744.96l17.92 61.44-66.56-37.12c-24.32 6.4-48.64 12.8-72.96 12.8-116.48 0-208.64-79.36-208.64-177.92 0-97.28 92.16-177.92 208.64-177.92 110.08 0 207.36 79.36 207.36 177.92C857.6 659.2 820.48 707.84 771.84 744.96z"  ></path>' +
    '' +
    '<path d="M716.8 524.8c-11.52 0-24.32 12.8-24.32 24.32 0 12.8 12.8 24.32 24.32 24.32 17.92 0 30.72-11.52 30.72-24.32C747.52 537.6 734.72 524.8 716.8 524.8z"  ></path>' +
    '' +
    '<path d="M331.52 346.88c-17.92 0-37.12 11.52-37.12 30.72 0 17.92 17.92 30.72 37.12 30.72 17.92 0 30.72-12.8 30.72-30.72C362.24 359.68 349.44 346.88 331.52 346.88z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-qq" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M512 0C229.227789 0 0 229.227789 0 512c0 282.745263 229.227789 512 512 512s512-229.254737 512-512C1024 229.227789 794.772211 0 512 0L512 0zM738.856421 637.062737c0 0-16.208842 44.220632-45.945263 83.941053 0 0 53.126737 18.054737 48.64 64.983579 0 0 1.778526 52.345263-113.461895 48.734316 0 0-81.071158-6.319158-105.377684-40.609684l-21.423158 0c-24.306526 34.304-105.350737 40.609684-105.350737 40.609684-115.280842 3.610947-113.475368-48.734316-113.475368-48.734316-4.500211-46.928842 48.626526-64.983579 48.626526-64.983579-29.709474-39.720421-45.918316-83.941053-45.918316-83.941053-72.057263 116.439579-64.848842-16.249263-64.848842-16.249263 13.527579-78.524632 70.238316-129.967158 70.238316-129.967158-8.111158-71.316211 21.611789-83.941053 21.611789-83.941053C318.410105 186.287158 508.025263 190.140632 512 190.248421c3.988211-0.107789 193.576421-3.961263 199.828211 216.643368 0 0 29.709474 12.638316 21.611789 83.941053 0 0 56.737684 51.442526 70.238316 129.967158l0 0C803.678316 620.813474 810.873263 753.502316 738.856421 637.062737L738.856421 637.062737zM738.856421 637.062737"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-weibo" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M411.270737 607.649684c-17.973895-7.504842-41.189053 0.229053-52.264421 17.542737-11.223579 17.394526-5.955368 38.103579 11.870316 46.201263 18.108632 8.232421 42.132211 0.417684 53.342316-17.421474C435.253895 635.944421 429.446737 615.370105 411.270737 607.649684z"  ></path>' +
    '' +
    '<path d="M455.545263 589.352421c-6.885053-2.721684-15.508211 0.579368-19.550316 7.329684-3.920842 6.790737-1.751579 14.524632 5.146947 17.367579 7.019789 2.883368 16.006737-0.458105 20.048842-7.370105C465.071158 599.740632 462.551579 591.912421 455.545263 589.352421z"  ></path>' +
    '' +
    '<path d="M427.52 469.315368c-115.968 11.439158-203.924211 82.216421-196.378947 158.073263 7.531789 75.910737 107.654737 128.161684 223.649684 116.749474 115.994947-11.439158 203.924211-82.216421 196.392421-158.140632C643.664842 510.140632 543.541895 457.889684 427.52 469.315368zM529.300211 648.299789c-23.673263 53.355789-91.769263 81.798737-149.530947 63.232-55.754105-17.933474-79.373474-72.811789-54.945684-122.246737 23.956211-48.464842 86.352842-75.870316 141.541053-61.561263C523.506526 542.437053 552.663579 596.143158 529.300211 648.299789z"  ></path>' +
    '' +
    '<path d="M512 0C229.241263 0 0 229.227789 0 512c0 282.758737 229.241263 512 512 512 282.772211 0 512-229.241263 512-512C1024 229.227789 794.772211 0 512 0zM455.531789 794.974316c-145.354105 0-293.941895-70.197895-293.941895-185.667368 0-60.362105 38.386526-130.182737 104.474947-196.069053 88.252632-87.929263 191.164632-127.986526 229.874526-89.397895 17.084632 17.003789 18.741895 46.457263 7.760842 81.623579-5.726316 17.690947 16.666947 7.895579 16.666947 7.936 71.343158-29.763368 133.564632-31.514947 156.321684 0.862316 12.139789 17.246316 10.954105 41.472-0.215579 69.510737-5.173895 12.921263 1.589895 14.928842 11.466105 17.879579 40.178526 12.422737 84.924632 42.455579 84.924632 95.380211C772.837053 684.638316 646.090105 794.974316 455.531789 794.974316zM718.672842 427.802947c4.715789-14.457263 1.765053-30.962526-9.202526-43.061895-10.954105-12.072421-27.136-16.666947-42.037895-13.527579l0-0.026947c-12.463158 2.694737-24.724211-5.268211-27.392-17.664-2.667789-12.463158 5.281684-24.697263 17.744842-27.338105 30.531368-6.467368 63.595789 2.937263 85.989053 27.715368 22.447158 24.764632 28.456421 58.489263 18.849684 88.064-3.907368 12.099368-16.936421 18.728421-29.062737 14.848-12.139789-3.920842-18.782316-16.922947-14.874947-28.995368L718.672842 427.816421zM853.261474 471.134316c-0.013474 0.013474-0.013474 0.080842-0.013474 0.107789-4.567579 14.026105-19.712 21.706105-33.778526 17.165474-14.133895-4.554105-21.854316-19.590737-17.300211-33.670737l0-0.013474c13.999158-43.169684 5.12-92.429474-27.567158-128.565895-32.714105-36.122947-80.949895-49.92-125.507368-40.488421-14.484211 3.085474-28.752842-6.130526-31.838316-20.574316-3.098947-14.403368 6.144-28.631579 20.641684-31.717053l0.026947 0c62.625684-13.271579 130.519579 6.117053 176.545684 56.966737C860.483368 341.113263 872.892632 410.381474 853.261474 471.134316z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-fuchanke" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M524.734521 58.885871c70.988181 0 128.609631 57.62145 128.609631 128.609631 0 70.988181-57.62145 128.609631-128.609631 128.609631s-128.609631-57.62145-128.609631-128.609631C396.305521 116.507321 453.74634 58.885871 524.734521 58.885871L524.734521 58.885871zM692.902452 434.780032l-30.165461 540.991357L401.182572 975.771388c0 0-161.665197-140.531311-158.594461-268.96031 0 0-4.515788-58.885871 92.122067-163.29088l163.29088-188.940554c0 0 61.956606-52.925031 146.672782 9.031575C644.493209 363.611219 688.386664 399.918151 692.902452 434.780032L692.902452 434.780032zM366.681954 744.382431l137.821838-130.054683 0-107.837008c0 0-18.06315-27.455989-29.98483 5.96084l0 86.522491-148.84036 141.976363c0 0-13.727994 42.809667 17.882519 83.813018 0 0 11.921679 20.591992 60.692186 18.785676l188.218028-161.845828c0 0 17.159993-21.314518 17.159993-45.338508l0-128.429c0 0-18.785676-17.159993-34.319986 1.625684l-1.625684 135.292997-175.393191 159.136356c0 0-27.455989 18.785676-38.47451-19.688834C359.817957 784.482625 355.663433 759.736109 366.681954 744.382431L366.681954 744.382431z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-zhongliu" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M896.654789 281.785147c-24.385253 18.966308-66.291762 5.599577-93.747751-29.623567-27.275357-35.223143-29.804198-79.297231-5.238314-98.082907 24.385253-18.966308 66.291762-5.599577 93.747751 29.623567C918.691833 218.925384 921.220674 262.999471 896.654789 281.785147zM898.099841 315.382607c0 0 2.167578 16.437467-2.167578 39.5583 0 0-168.348562 137.641207-309.241136 113.978479-151.188569-25.288411-217.119069-166.180984-223.621803-265.34768 0 0 14.811783-38.835774 69.001235-68.098077 64.304816-34.86188 179.547716-56.176398 328.026812 17.701887 0 0-35.042512 49.854295 4.515788 108.559534C798.391251 311.950609 844.994179 326.943023 898.099841 315.382607zM663.459517 240.781796c-2.167578-44.43535-38.293879-76.587758-83.090492-68.639972-44.796613 7.947786-77.129652 43.893456-74.962074 81.826072 2.167578 37.932616 38.293879 63.401658 83.090492 55.453872C633.294055 301.654613 665.446463 278.714412 663.459517 240.781796zM587.774916 279.436938c-24.746516 4.154525-45.880402-8.670312-47.144823-28.901041-1.264421-20.230729 17.882519-39.919563 42.629035-44.074087 24.746516-4.154525 45.880402 8.670312 47.144823 28.901041C631.668372 255.593579 612.702064 275.282413 587.774916 279.436938zM330.194391 196.88834c0 0-47.144823 21.856412-74.600811 50.39619 0 0-39.5583-7.586523-103.140589 52.563768 0 0-13.366731-44.615982 9.57347-93.386488C191.469395 143.602046 268.237784 78.03281 373.907215 139.989416 373.907215 139.989416 346.631857 148.659728 330.194391 196.88834zM190.204974 312.853766c37.571353-31.971776 78.574705-36.848827 78.574705-36.848827 35.403775 73.336391 53.105662 150.827306-8.489681 227.595696-39.197037 48.951138-116.507321 35.584406-116.507321 35.584406C94.109014 438.031399 139.989416 355.482801 190.204974 312.853766zM219.64791 703.198448c9.031575 51.118716-14.631152 114.339742-14.631152 114.339742-69.001235 5.780208-126.08079-18.06315-131.319104-108.559534-2.709473-47.325454 39.197037-84.354913 39.197037-84.354913C180.270242 630.042688 212.242018 661.111307 219.64791 703.198448zM137.099312 596.083965l4.335156-24.204622c48.951138 6.502734 134.028576 6.141471 179.367084-104.766273 41.364615-101.334274-23.662727-208.810019-23.662727-208.810019l35.042512-24.204622c16.256835 77.671547 70.446287 224.163697 219.286647 263.180102 160.942671 42.087141 335.613336-94.289645 335.613336-94.289645l2.167578 30.707356c-58.885871 29.98483-136.376786 77.490916-184.063503 188.579291-49.673664 115.965426-35.223143 254.329159-35.223143 254.329159l-21.856412 2.167578c1.625684-133.30605-80.742283-248.729582-210.616334-230.305168-144.324572 20.591992-175.393191 195.262657-175.393191 195.262657L240.239901 821.873346c14.45052-48.770506 31.068619-102.237432 3.070736-155.523726C214.409596 611.257012 137.099312 596.083965 137.099312 596.083965zM323.511025 604.754278c18.785676 31.610513 71.530076 40.461457 116.146058 13.1861 44.615982-27.275357 55.995766-73.517022 37.21009-105.308167-18.785676-31.610513-76.045863-42.629035-120.661845-15.353678C311.769977 524.55389 304.725348 573.143764 323.511025 604.754278zM378.061739 525.81831c27.275357-15.714941 58.705239-13.547363 70.265655 4.696419 11.560416 18.243782-1.264421 45.519139-28.539778 61.23408-27.275357 15.714941-58.705239 13.547363-70.265655-4.696419C337.961545 568.808608 350.786382 541.35262 378.061739 525.81831zM468.19686 678.451932c154.981831 22.037044 145.047098 181.715294 146.853413 224.705592 0 0-48.951138 44.796613-118.133004 56.357029-70.80755 11.741048-162.568354-10.657259-208.629388-87.064385C288.468513 872.450168 305.447874 679.174458 468.19686 678.451932zM895.932263 466.751808c41.364615 71.168813 61.595343 170.696772-11.018522 298.764509-66.291762 116.868584-177.560769 122.46816-177.560769 122.46816-6.863997-69.001235-1.445052-200.862233 44.977245-285.578409C803.08767 509.380843 895.932263 466.751808 895.932263 466.751808z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-biao" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M512 953.6c-243.2 0-441.6-198.4-441.6-441.6 0-243.2 198.4-441.6 441.6-441.6 243.2 0 441.6 198.4 441.6 441.6C953.6 755.2 755.2 953.6 512 953.6zM512 128C300.8 128 128 300.8 128 512c0 211.2 172.8 384 384 384 211.2 0 384-172.8 384-384C896 300.8 723.2 128 512 128z"  ></path>' +
    '' +
    '<path d="M505.6 518.4 505.6 339.2 448 339.2 448 576 652.8 576 652.8 518.4Z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-yaowudrugs4" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M1018.624 330.144c-10.912-56.512-43.2-105.44-90.88-137.632-35.84-24.256-77.504-37.056-120.608-37.056-71.776 0-138.56 35.456-178.72 94.752l-221.344 327.424c-32.256 47.712-44 105.088-33.056 161.632 10.912 56.512 43.2 105.408 90.88 137.6 35.808 24.288 77.504 37.056 120.608 37.056 71.744 0 138.528-35.456 178.656-94.784l221.344-327.328C1017.792 444.032 1029.536 386.688 1018.624 330.144zM836.896 285.824c-5.536-1.248-52.032-10.176-91.072 47.456l-58.688 86.816c-6.944 10.24-18.272 15.808-29.792 15.808-6.944 0-13.952-1.984-20.096-6.208-16.448-11.104-20.768-33.44-9.632-49.888l58.688-86.784c69.632-102.976 165.376-77.632 169.408-76.48 19.136 5.344 30.304 25.12 24.96 44.224C875.36 279.68 855.808 290.752 836.896 285.824zM704.672 778.848c-26.752 39.584-71.328 63.232-119.168 63.232-28.672 0-56.512-8.544-80.384-24.704-31.776-21.472-53.312-54.08-60.608-91.776-7.296-37.696 0.576-75.936 22.048-107.712l88.352-130.688c13.12-19.392 39.52-24.512 58.944-11.392l167.744 113.408c9.344 6.272 15.776 16.064 17.888 27.104 2.144 11.072-0.192 22.496-6.496 31.84L704.672 778.848z"  ></path>' +
    '' +
    '<path d="M445.216 377.12c1.792-122.08-95.68-222.528-217.792-224.384-122.08-1.792-222.56 95.68-224.384 217.824-1.824 122.08 95.68 222.528 217.792 224.32C342.944 596.704 443.392 499.264 445.216 377.12zM347.936 413.12c-0.288 0-0.64 0-0.96 0l-247.552-6.528c-19.808-0.544-35.488-17.056-34.944-36.928 0.544-19.776 16.224-35.232 36.832-34.944l247.552 6.464c19.808 0.512 35.456 17.024 34.944 36.864C383.264 397.632 367.328 413.12 347.936 413.12z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-zhenjiuke" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M512.19187 44.549635C256.699941 44.549635 49.58379 251.665787 49.58379 507.158738c0 255.485789 207.116151 462.602963 462.60808 462.602963 255.489882 0 462.606033-207.116151 462.606033-462.602963C974.798926 251.665787 767.681752 44.549635 512.19187 44.549635zM283.628244 745.632061c-36.287461 30.738072-32.107255 0.684592-24.713868-7.391341C338.533738 651.838885 412.467611 528.615423 382.208447 405.799236c-10.335393-42.171472-29.91738-86.874603-63.389726-115.901707-10.956539-16.978696 15.264659-19.167548 23.476691-11.981892 36.627199 31.766494 61.615313 82.222653 71.40426 128.907928C440.607508 535.12058 367.282502 654.715399 283.628244 745.632061zM508.447593 648.969535c-13.967106 0-25.329898-11.299347-25.329898-25.329898 0-13.967106 11.299347-25.330922 25.329898-25.330922 14.036691 0 25.330922 11.362792 25.330922 25.330922C533.778514 637.600603 522.484284 648.969535 508.447593 648.969535zM747.715001 744.469586c-82.699514-101.458763-141.504828-232.900398-106.932428-366.466416 13.142322-50.723242 39.634696-104.672968 79.479169-139.998521 8.215102-7.256264 34.091446 4.722558 25.88146 12.257162-36.081777 32.03767-61.203944 80.779791-73.596182 126.511345-35.053354 129.727597 26.562982 249.327532 94.132914 355.713514C779.958356 751.654218 754.628458 753.025449 747.715001 744.469586z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-jiantou-copy" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M632 477.952h-376.256v68.096h376.256l-142.272 136.32h100.609l177.92-170.368-177.92-170.368h-100.609z"  ></path>' +
    '' +
    '<path d="M8.64 512c0 277.952 225.408 503.36 503.36 503.36s503.36-225.408 503.36-503.36-225.408-503.36-503.36-503.36-503.36 225.408-503.36 503.36zM983.999 512c0 260.672-211.328 472-472 472s-472-211.328-472-472 211.328-472 472-472c260.672 0 472 211.328 472 472z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-jiantou-copy-copy-copy" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M406.531 541.925h330.694v-59.85h-330.694l125.044-119.813h-88.427l-156.375 149.738 156.375 149.738h88.427z"  ></path>' +
    '' +
    '<path d="M954.406 512c0-244.294-198.113-442.406-442.406-442.406s-442.406 198.113-442.406 442.406 198.113 442.406 442.406 442.406 442.406-198.113 442.406-442.406zM97.157 512c0-229.105 185.739-414.844 414.844-414.844s414.844 185.739 414.844 414.844-185.737 414.844-414.844 414.844c-229.105 0-414.844-185.739-414.844-414.844z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-erke" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M512.0001 1004.885333c-62.323451 0-122.819765-12.167529-179.762196-36.181333a460.277961 460.277961 0 0 1-146.773333-98.625255 458.591373 458.591373 0 0 1-98.966588-146.291451A456.041412 456.041412 0 0 1 50.196179 544.627451c0-62.102588 12.207686-122.378039 36.301804-179.159843a458.551216 458.551216 0 0 1 98.94651-146.271373 460.318118 460.318118 0 0 1 146.773333-98.625255A460.277961 460.277961 0 0 1 512.0001 84.389647c28.451137 0 56.902275 2.590118 84.530196 7.669961a59.65302 59.65302 0 0 0 43.289099-47.465412c3.011765-18.69302-3.292863-32.446745 4.557803-40.638745 12.488784-13.010824 35.659294 9.657725 44.032 17.347765 11.444706 10.480941 21.845333 28.210196 27.668079 40.678902 10.420706 22.327216 11.203765 46.802824 2.268863 70.816627a461.000784 461.000784 0 0 1 120.18949 86.39749 458.571294 458.571294 0 0 1 98.966588 146.291451A456.081569 456.081569 0 0 1 973.804022 544.647529c0 62.102588-12.207686 122.378039-36.301804 179.139765a458.551216 458.551216 0 0 1-98.966588 146.291451 460.298039 460.298039 0 0 1-146.773334 98.625255A460.298039 460.298039 0 0 1 512.0001 1004.885333M213.473983 287.804235a389.421176 389.421176 0 0 0-71.157961 119.42651 391.188078 391.188078 0 0 0-24.676392 137.396706c0 104.990118 41.020235 203.695686 115.511215 277.925647A392.45302 392.45302 0 0 0 512.0001 937.662745a392.45302 392.45302 0 0 0 278.849255-115.109647c74.49098-74.229961 115.511216-172.935529 115.511216-277.925647a389.441255 389.441255 0 0 0-95.834353-256.843294c-24.616157 36.823843-79.550745 59.171137-110.110118 69.210353-54.492863 17.889882-119.647373 27.366902-188.416 27.366902a676.643137 676.643137 0 0 1-122.839843-10.88251 32.627451 32.627451 0 0 1-22.548078-16.143059 32.426667 32.426667 0 0 1-1.907451-27.607843c19.777255-50.738196 45.136314-80.51451 62.945882-96.537098-44.634353 17.608784-81.960157 48.790588-110.893176 92.661961a32.707765 32.707765 0 0 1-42.124549 11.103372c-28.431059-14.456471-49.011451-31.021176-61.158902-49.152m301.136313 547.538824l-2.971607-0.020079c-78.58698-0.602353-150.628392-23.592157-197.672157-63.146666a33.53098 33.53098 0 0 1-4.015687-47.385098 33.792 33.792 0 0 1 47.525648-3.995608c34.675451 29.153882 92.501333 46.84298 154.664156 47.304784 62.644706 0.501961 120.109176-17.02651 153.760628-46.762667a33.812078 33.812078 0 0 1 47.585882 2.831059 33.53098 33.53098 0 0 1-2.831059 47.465412c-45.879216 40.558431-117.197804 63.688784-196.045804 63.688784m75.615373-290.55498a54.071216 54.071216 0 0 1 54.151529-53.990902 54.071216 54.071216 0 0 1 54.171608 53.990902 54.071216 54.071216 0 0 1-54.171608 53.970824 54.071216 54.071216 0 0 1-54.151529-53.970824m-264.774274 0a54.071216 54.071216 0 0 1 54.171607-53.990902 54.071216 54.071216 0 0 1 54.15153 53.990902 54.071216 54.071216 0 0 1-54.15153 53.970824 54.071216 54.071216 0 0 1-54.171607-53.970824" fill="#515151" ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-icon" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M502.807 28.179c-269.77 0-488.462 218.71-488.462 488.462s218.704 488.462 488.462 488.462c269.758 0 488.462-218.704 488.462-488.462 0.001-269.758-218.704-488.462-488.462-488.462zM708.912 682.571c0 7.786-6.324 14.101-14.101 14.101 0 0 0 0 0 0h-227.585l-101.81 74.074 0.001-74.074h-54.668c0 0 0 0 0 0-7.786 0-14.101-6.318-14.109-14.101v-337.421c0-7.786 6.325-14.109 14.109-14.109h384.034c7.786 0 14.101 6.325 14.101 14.109v337.421z" fill="" ></path>' +
    '' +
    '</symbol>' +
    '' +
    '</svg>'
  var script = function() {
    var scripts = document.getElementsByTagName('script')
    return scripts[scripts.length - 1]
  }()
  var shouldInjectCss = script.getAttribute("data-injectcss")

  /**
   * document ready
   */
  var ready = function(fn) {
    if (document.addEventListener) {
      if (~["complete", "loaded", "interactive"].indexOf(document.readyState)) {
        setTimeout(fn, 0)
      } else {
        var loadFn = function() {
          document.removeEventListener("DOMContentLoaded", loadFn, false)
          fn()
        }
        document.addEventListener("DOMContentLoaded", loadFn, false)
      }
    } else if (document.attachEvent) {
      IEContentLoaded(window, fn)
    }

    function IEContentLoaded(w, fn) {
      var d = w.document,
        done = false,
        // only fire once
        init = function() {
          if (!done) {
            done = true
            fn()
          }
        }
        // polling for no errors
      var polling = function() {
        try {
          // throws errors until after ondocumentready
          d.documentElement.doScroll('left')
        } catch (e) {
          setTimeout(polling, 50)
          return
        }
        // no errors, fire

        init()
      };

      polling()
        // trying to always fire before onload
      d.onreadystatechange = function() {
        if (d.readyState == 'complete') {
          d.onreadystatechange = null
          init()
        }
      }
    }
  }

  /**
   * Insert el before target
   *
   * @param {Element} el
   * @param {Element} target
   */

  var before = function(el, target) {
    target.parentNode.insertBefore(el, target)
  }

  /**
   * Prepend el to target
   *
   * @param {Element} el
   * @param {Element} target
   */

  var prepend = function(el, target) {
    if (target.firstChild) {
      before(el, target.firstChild)
    } else {
      target.appendChild(el)
    }
  }

  function appendSvg() {
    var div, svg

    div = document.createElement('div')
    div.innerHTML = svgSprite
    svgSprite = null
    svg = div.getElementsByTagName('svg')[0]
    if (svg) {
      svg.setAttribute('aria-hidden', 'true')
      svg.style.position = 'absolute'
      svg.style.width = 0
      svg.style.height = 0
      svg.style.overflow = 'hidden'
      prepend(svg, document.body)
    }
  }

  if (shouldInjectCss && !window.__iconfont__svg__cssinject__) {
    window.__iconfont__svg__cssinject__ = true
    try {
      document.write("<style>.svgfont {display: inline-block;width: 1em;height: 1em;fill: currentColor;vertical-align: -0.1em;font-size:16px;}</style>");
    } catch (e) {
      console && console.log(e)
    }
  }

  ready(appendSvg)


})(window)