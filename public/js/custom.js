function togglechk(A) {
    $("input[name=" + A + "]").click()
}
function onErrorTeam(source) {
    source.src = baseUrl + 'public/images/default_team.png';
    source.onerror = "";
    return true;
}

$('.d-tooltip').tooltipster({
    delay: "50",
    distance: -5,
    maxWidth: "300",
    theme: "tooltipster-punk",
    contentAsHTML: "true"
});

$(".tooltip").tooltipster({
    delay: "50",
    distance: -5,
    maxWidth: "300",
    theme: "tooltipster-punk",
    contentAsHTML: "true"
}), $("#filtertoggle").click(function() {
    $("#filter").is(":hidden") ? ($("#filterimg").attr("src", "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAADIklEQVR4Xo2QbUyVdRyGr+c5zznE+5vFSCTIxVbkGmQxJ5sWk7LMtblSP7S2Gg6rrVmNaubItci5GquFfiWbojScK4eCcNISEUQoVyBsQSgiL3HwnMN5DnDO8//1H3La+tDWtV27v927dxsb9595qOyJgld+GfVtQZwCUSpRBKJKke4x8S9EWYgoBEGUFkI6hlZlJpwem/R/a5RWN0v9+5s53jNJbto95Ka4MU0DMUAcISpCJBK9q+MwOBFgaDLIurwMjv94HUuJsDoePqo/S0ZaKukpCaQmJZGWqDM5HrflJjBn4w/ZBGydQZtp/xwfP7eVo94BLICgwKX9LzE8HWR0xmZwKsS0rVBKtApHZ8G9CayITybBbZCd5GHcH0YEKHnvWHvPjMgfAZGGoUXpnHDkz6DIzaAjPbfD0j0WkoEpW67cCMrR3gnZ+8OgXBqelRNXx2RdVUO7ATy4cd/Jw2/vLCvPykji57EFlHIILywyr7W14fnIkpFIhBfX3IcvGKa+ubv16sHtu12bPm2Z9Va/0HEzqejhB/JyVt+fEs/soiLOMkAAQERAW5KfznggwjHvtea+A9veWF97ZYTymla2fd0JkF/6wYmW2k6f1PfPS22fLTVds7LvwpTsab0tn3TekT3t07L1ra+6gHzvo5l416zABDAMg+c/Pz9y8cD2ypPfn2u9MR3Ccrn0CjdWXBye1DSyj1RT9tpKqvq+fPJUYdZw0Eo4ZHuSsQBiJVu+uDBy+t0NlYbRWFe4du3mRwpylr7Ira+iaPAsxRWvgycOHEVXx+XdE+NTxvIC7rJc8lPNy2/+2vs7uwrdDNyaJe/MIYqfLYecfEjPguxVlGxYjxONVlp6Pv47AQBiPfrYkfnFKP1zYLpciOmC4lIo30EM2r5DjjRixebHiBWpcMB7/re/nvZYJpbLhMbDRBvqUErpUhPTHYehy03+DbHCjoM7K5pOtbXYMzN0F+3gcv8oSBS3SxBxuHj9Fj5Fk/HMZ+f4L1o+3JT/VE1br52YmbarruybjMSUV5VhggG+iNNUcW30Hf4HjwGPAyuXM2YuwN9eaYvP8zUR2AAAAABJRU5ErkJggg=="), state = 1) : ($("#filterimg").attr("src", "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAADG0lEQVR4Xo2RXWxTZRzGf6en3dqtG1sZ4vwYTMISkiU6v0mWSCTDTCcJokMu0CvJwAsTPzGIgMpQmC4a425HlC+jJH5krBubRYRlbBYNOtiYqxtT2w1WespO17Xn/Xty0mRXJj7JL8/7XrzP/5/n1dbsPbly7QNVW34Zn2lArCpRqlAEskpRmucikc6SzigEQZQNzNo2cufigu8nY4nPtdrdHdL+Rj3HBmNUlHipKPbgcmmIBmIJWREymSwOlsVw1GAklmT18gDHfriMW4mwwgdvtXcSKFlEaXEBi/x+SgptL/LhcXswbpokZk0M0/akyXTiJnseX8/h3ku4AZIC5/Y+w9h0kvHrJsNTs0ybCqXERmHZXrWkgDJfEQUejXJ/Hn8nUogAD716pGfwusgfhsjRkXnpi1ryZ1LkatKSwX9Scn5yVi5NmTIwkZTD4ajs/G5Yzo3F5fjPk7L69aM9GnDXml0n2l7avHbd0oCfM5NplLJIpeeZszFtUnMZh0wmw4bqW5hJpjjUMdA1cLBxm163Lxjv3f3k2av+mlXLlt+x4rZiH/F5Rb5bA8GRiKApeHhZgFg8y5edFzvOt2zYXrcvHGFdcxcbP+0DqKzdcTzY2jcj7UNz0nrBlOb+uOwKTckrnVH54LRhrx+X2p0H+4HKpz6GjZ+AC0DTNJ5oCUV+en9T04lvu7smpmdx67q9hYf8PC9FvjKGI9vpv1hKddWeB+sPMJa1+EypXEAuhIYPT0fO7N/UdCrYc3Jo9C/7cR7pjIfR0edAvuDZBni7sY2n6+HWJWyzFG25DXBELuTH5sYXfw3/ztZqD1cmbhCLHeHR+wsp895DuXcLZfk1PFITIGvR5LYnk7hhAJDLwS42MjefZcgAXdedAasWb+Xe8o8AWL8yTDj6GiItCx3YgINzRqWM3tBv1/C4Xbh1CF1p5T3n13E8NNKC7lroYIFc4NkDm1/46ptTwWT8GiINXLgMukDf+A7H7Ttpg6+1x/Z3818KvllXWfduT1i0pSWkqg95AzzvNK/hPO5+h5f5H7obuA+43fEFKgD+BdDWi/BunUBoAAAAAElFTkSuQmCC"), state = 0), $.cookie("cs_filter_state", state), $("#filter").eq(0).toggle()
})

$(".projectLink").click(function() {
    var postData = {projectId: $(this).attr("data-id")};

    $.ajax({
        url: baseUrl + 'ajax/clickProjectLink',
        data: postData,
        dataType: 'json',
        type: 'POST',
        success: function(jsonData) {

        }
    });
});

$(".clickPartner").click(function() {
    var postData = {id: $(this).attr("data-id")};
    $.ajax({
        url: baseUrl + 'ajax/clickPartner',
        data: postData,
        dataType: 'json',
        type: 'POST',
        success: function(jsonData) {

        }
    });
});