<?php
require_once __DIR__ . "/php/inc/header.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Organization Chart</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/es6-promise/4.1.1/es6-promise.auto.min.js"></script>
    <script type="text/javascript" src="/js/html2canvas.min.js"></script>
    <script type="text/javascript" src="/js/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/orgchart/4.0.1/js/jquery.orgchart.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/orgchart/4.0.1/css/jquery.orgchart.min.css" />
    <style>
        .orgchart {
            background: white;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            padding: 1px;
            box-sizing: border-box;
        }

        body {
            font-family: Arial;
            color: #333333;
        }

        #chart>div {
            position: relative;
            height: 520px;
            border: 1px solid #aaa;
            margin: 0.5rem;
            overflow: auto;
            text-align: center;
        }

        .nodal {
            display: flex;
            gap: .5em;
            background-color: #eee;
            border: none;
            padding: 1em;
            border-radius: .5em;
        }

        .nodal .name {
            font-weight: bold;
            font-size: 1em;
        }

        .nodal .title {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #a52121 !important;
            padding: .2em;
            width: unset !important;
        }

        .nodal img {
            width: 2em;
            height: 2em;
            object-fit: contain;
            border-radius: 2em;
        }
    </style>
</head>

<body>
    <div id="chart"></div>
    <script>
        fetch('/api/getEmployees')
            .then(response => response.json())
            .then(data => {
                const buildHierarchy = (data) => {
                    const map = new Map(data.map(item => [item.id, item]));

                    const root = data.filter(item => item.parent_id === null);

                    const buildNode = (id) => {
                        if (!id) {
                            return null;
                        }
                        const node = map.get(id);
                        node.children = data.filter(child => child.parent_id === id).map(child => buildNode(child.id))
                        return node;
                    };
                    return root.map(e => buildNode(e.id));
                }
                buildHierarchy(data).forEach((data, index) => {
                    const chartDiv = $(`<div class="chart${index}">`);
                    $('#chart').append(chartDiv);
                    $(chartDiv).orgchart({
                        data,
                        nodeContent: 'title',
                        'createNode': function($node, data) {
                            $node.on('click', function(event) {
                                if (!$(event.target).is('.edge, .toggleBtn')) {
                                    var $this = $(this);
                                    var $chart = $this.closest('.orgchart');
                                    var newX = window.parseInt(($chart.outerWidth(true) / 2) - ($this.offset().left - $chart.offset().left) - ($this.outerWidth(true) / 2));
                                    var newY = window.parseInt(($chart.outerHeight(true) / 2) - ($this.offset().top - $chart.offset().top) - ($this.outerHeight(true) / 2));
                                    $chart.css('transform', 'matrix(1, 0, 0, 1, ' + newX + ', ' + newY + ')');
                                }
                            });
                        },
                        'exportButton': true,
                        'exportFilename': 'MyOrgChart',
                        nodeTemplate: function({
                            photo_url,
                            name,
                            title
                        }) {
                            return `<div class="nodal" title="${name}">
                                        <img src="${photo_url}" alt="${name}" />
                                        <div>
                                            <div class="name">${name}</div>
                                            <div class="title">${title}</div>
                                        </div>
                                    </div>`;
                        },
                        zoom: true,
                        pan: true
                    });
                })
            });
    </script>
</body>

</html>