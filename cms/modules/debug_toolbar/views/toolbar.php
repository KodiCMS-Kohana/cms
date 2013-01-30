<?php defined('SYSPATH') or die('No direct script access.') ?>

<!-- CSS styles (if not added to <head>) -->
<?php if (isset($styles)): ?>
	<?php echo $styles ?>
<?php endif ?>

<!-- Javascript -->
<script type="text/javascript">
<?php echo $scripts ?>
</script>

<div id="kohana-debug-toolbar">

	<!-- Toolbar -->
	<div id="debug-toolbar" class="debug-toolbar-align-<?php echo $align ?>">

		<!-- Kohana link -->
		<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAAAUCAYAAAB7wJiVAAAACXBIWXMAAAsTAAALEwEAmpwYAAAOImlDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjarVdnPNUP3/7+znLMrMxwkJE9srJ3ZmYdI3uG45ghpCiylU1Ikqi/ElEyyt5KiRJlFKmsiMS5X/S/n/+L5/M895v7enV9rxff+eL6fAEOcLiSyf4oAAgghQZbGeoQiHb2BKo3QA10QAPcIObqHkLWtrAwhf8TW+OAAACMSbqSyf7pwyXxwgTB2G4HW94fRr3M8P+DIZhoZw+ASAAAq/cfrgUArG5/uA0AsJ4NJYcCID4AwOru4+oBgEQDgESwjZUuAFIFAAzef3gTADC4/eG9AMAQ7u4dCoBMAOCYSR6+JACqrwA4DQ/PEHcABgkA8PAIcQ8AYMgEAKmAgEAPAIZ2ABB1JweHAjAsAoAk0c6e8Kdl+14A+RoAqox/tHB3gIYkAOGgfzShGgC2RYBbQv9oG1aAAADCNhLiJS8HAAAInQ4AdppC2RAGoLoKsJdFoeyWUyh7NwHQUwDt/u5hweF/7wtBhgD+U/xn5r+BRgBQAIgDagMTgV2h8sXP0XjSztKfYhhjtGLqZ9FnbWM7xt7AKc5VdoiTJ5MPT4jhXxF0P/xK2ECk7ghBLE18S9JFqktGQjZdbuWoucIdJZSyg8p9VYyarXqFxqqWqna8To8enb65QZrhgBHOWNsk0vS+2cIJTgtTyyirausJW8xJmVMOxIt2d+xHHbZOczupO7u6XHC94dbuPu2x68XhLe9j7utz5rxfrv+9gOekN4HL5L3gAyF8odJhGuEmZ4kRHpGkqIhzcdFJMRmx2ecL4oovlFwsi7+RcONS2eWSxKKk/CvZyRkpV1Lj06LTQzL8Mt2yiFfNr+lmK+dI5BLyWPOp8ncKvhROFQ0Vt16/V3K9NKUs6oZ3ue1NnQqpW1yVuMq12++quu7UVufXxN31uWfxl1Itz330/cUHQ3V1D3PrIxscH2k28jehmuYfdz6paE546t6i3crXutc22f7oWdZzvw79Tr7On10j3ZU90b1WfcJ9v/qHBkoGSUMaw3TDb0cqR4NeqL7EvBwau/aK+Jrn9Yfx8jduEwITHydL3tq/Y3s3OnXlvfb7nenaGY8PnB+GPsbNys3Oz+XM689vLdz6ZPMZ8/nhotsS01L7F/IyYXn064Vvct/mv+evmK4iq41rpHWR9Y8bxT/sNtk3x7fyftpv82zP7lT9Iu8q/0b9HtjL3XelSFMof9/fATtBdRL/kuY4bSv9UYYaRn6mXBZm1oSDu+wkjnkuG+5unmO8dwmH+JMFfh32EhoVURUtF8OLB0iMSMlJZ8l8lzOQLz26pWikVKS8dExRNVatW4NG00wrXXtQl1pPV/+cwX3DBSMOYyOTCNNKszHzPQsRS0urCOvrNs9tP5+iIUranbAnOaQ73js94LTognXld1N1P+lB8kz0KvFu8BnwnTmz4U8VwEkSC1QlGwcRg71CQkLPhyWHZ58tjqiIrIl6cK4xujmmLfbZ+edxHRc6LnbEP0tou9Ry+XFifVLtlerkWyklqXlpmelJGeczw7P8r7pfs8s2z9HNVc6TzBcoYCukLtwrWi2evf6qpLu0qaz6RlF56s3oioBbjpWmt1WrxO5wVeOrt2rm77681/bX3drC+5cfhNQ5PzSpV2oQeET/aKdxrmn4cdOT8ubUp2EtTq2GbdLt7O37zz49H+p42FnQFdvt3mPYK97H0LfWPzZQP5gzFDpsPSI3yjj69UXvy5tjsa+Ir2XG8eMf3jyaSJl0fiv/DvduYurO+8hpoxn2mYUPdR9jZ43mWOem528vkD8pfdr/3LmYtGT6hfHLi+WrX62+MX0b/p68or+yv9qwdmadd310I+6H1I+3m/FbYlsjP4O2mbdrd4x35n9F7TLulv8++rtjz3JvZv/M/iYlikIBQNAoCbQmxhbrg4ulysXfoW6hGaddpkcxsB4QYVRjsmR2YwljTTxYwFbD/oSjn3OS6zP3Fg+Kl4aPjcDHLyQgLih1WEZIVlhWREpU7IiwGI84iwRe4pfkstQ76R6ZOtliuUvy/ketFBQV2RW3lSaUm1RyjgWrmquJqWPVpzWaNLO0fLV1dLh01nX79cr1owysDMWOo46/NXpgnGzibqpqxmr2zbz7RJlFpKW1laQ1zvqDzWPb7JOkU8eJAsTfduP29x2SHT1PqzuxO60597vcdI12O+ku5UHlMePZ6JXu7eWj7svqu3zmuV++PylAj8RJ+h7YSc4PCgjWCmENWQxtDksLdzkrF4GJGI+sioo8ZxzNHf015mls2vnTcVJx+xdGLpbGkxO0LzFfmrtcn5iYRLwidmUv+UVKRWpEmmk6f/pWxmBmeVbkVYtrotmQPZlTl5ua55WvXXCoYLvwddGD4ozrASUmpWJlNGVfbgyW37uZURF862Tlsdt8Vdiq5Ttj1c01FXfT70X+5VlrdV/rgXQd30Omekz9dsP3RwuN75vePB57Mto8+vRly3jrVNtC+9ozSgdjp1CXerddT0RvcV9n/8ag0JDTcPHIhxfiL2PGXr9WHi+bYJpMeUc/lTctMdP3MXCOb/7Np8JFjy9KX5m+bay8Xxva6Nrs+Nmy077bvzdFoQD88T4AAJwiQB4jwKnDANblAImFAKK2AGxVABb0ADYqgLIrAJRTEyBeXv/2D0ABFmiBGbhBGORAC06AMwRBAhRALXTDNGwjLIg0Yob4ISnIXWQYWUexo9RRHqhU1CPUBzQdWgXtg85D96J/YSQxbph8zAiWCquNjcY2YbdwCrgwXCPuF5Um1SWqITwb3hV/D79LbUxdRP2dRocmn2aV1oi2gg6hc6Frpxegv0z/lcGaoeWA6IEcRgxjKONnJgemF8zGzJ0smiwtrOqsrQe1D/awmbO9YXdj/84Rw8nAWcalwDXA7cG9f6iIR4Vnkjeaj8DXTQjk5+LvFggTFBacPJwuZCBEEW4WiRRVEf195JlYkriVBK/EN8lWqSxpbxlNWS7ZbbkJ+SdHSxUSFYOUnJTNVDSPyauKqQmq82nwavJqCWiL6sjoquod1ycanDGMPZ5jVGs8YLJkRmMudcLGIsayymrcBmercjLw1G3irD3BwcXx5uklZzmXGNcB90MeZM8eb4JPtO+Un4b/TRJtYDh5LtgmpDdMM/xxhFJk4znV6OexZuenLpDiMQkll9USp68kpEinzqRfyzS7SnttJCc/z7tArYit+GfJdNlgeXtFc+XTqq7qV3eXa/EPJB8SG5IbO56gnxq1ZrcvdKh15fVs9hMHn44IvkgeWx+3n2h/d/j95ZlPszrzRZ/WlnSXr36bWT2yTv7xaGtnR3M3fq+LQvmf+7PAIRABedAGC3CFEEiEYqiDfpiDPYQTUUCskRDkGtKATCC/UQIoI1QQqhDViVpF86BN0OfQNej3GCaMPuYcpg6zjBXCumCvY9/huHAOuOu4j1QiVCSqR3jAm+IL8IvUKtQp1B9oFGjSaD7T6tCW0u7TOdM9oxemT6XfZHBmGDigcuAOIzdjBhOGKZppizmI+TtLAMsKazDr1sEYNhxbJvsh9hoOVY4hTlfOn1zp3KLcHYdceYCnnFefd5nvKkGV8Jk/R0BPYEuw+rCLEIfQmHCmyAlRRtGxIwViruIS4jsSvZKFUiRpPRkemR3ZN3KN8oVHzyt4K1oqqStLqPAeY1bFq4HarvqOxrbmL619Hawugx6nvrCBouHx445GwcbJJrdMn5t9PIG2ELE0swqzLrUZtN09JUl0scu1H3akPq3vlODc7UrtdsI9z2PWS8o7xmf4jIDfWf8RkljgZfJCsEFIdRhjeMTZuUiLqPZo+Ziq83xxBRfZ43MvcV4uTRK9Up+im/o63S8Tk3Xjmnb2Qm5avkrBUlHxdZtSxrLR8pwKp0rx23t3XtXU3suoDXlw+qFJg0ajwmO5ZvmWY20Gz051kLpSemr7JgdphnVHE14Ovz78JmFyderM9MbHtHnlT1tL/V8frjxc79v8uSP3+yKFAgBooAYWIIAM6AIRyJACVdADSwg9chQ5jSQhDcgCih1lgrqIeoLaRMujg9H16G2MBiYRM4YlYMnYDhwHjowboBKlSqJaxlviH1MLUWfT4GiiaX7QkmlX6ULodujjGRgZbhxQPDDM6MuEZ6phtmT+xVLJevIg9cE2tgj2o+zrHPWcEVya3HjuV4du8oTyGvLx8G0SRvnvCqQKBhy2FFIW5hehE9kR/XJkSuyFeJ9Eh2SbVJt0u0yX7KDcuPzs0TVFtBKHsoyK8TFv1US1avURjW0tQW0rnXjdRr3vBkcMvY5XGn0xkTGNMus+cdDC27LFmtUm0HbolCQxw+6Hg6Njl5Osc6nrAbc49x+evl4zPkTfF36m/n0kw8DuIIPgnlDjsOGz1hGTUS7nFmOCYnfjEi+yxJddkr7cmmR+ZTqFnIZKz8kUzXp6zTL7U+65fMaCyiK14hcl3qV7N67eFKl4Wmlxe/5OeA3+bsFfR2qbHhjUvax3bJhv9GtaeRLUvNZCal1sd3421qHX+aCbu+dC72y/1kDh4Mqw9kj66PhL7jGHV/mvh9+gJmQnT7+Nf3d7quf93PTvD0wfBWZl59Tm9RdMPpl9Nl80WTL8orWs/FXyG+E78wqysrb6fq1v/eHG9R8Jm35bFj8Vtnl2MDuLvwZ3a39n7gXum1OEKRSAP/8SAADQ6Ab6BwYTTHX14L+LAP+wf9dAAIDOk2RrDQDMAHAIdCEQ/CEQgoEApqALegB/fjUAABwjQLETAEC7cNn/yhvqGREKAKAbSI4M9vX2CSVok8n+ngTdwAByWKhnsATBiOQuJUGQk5FRAAD4F4xzFCEUMC4uAAAAIGNIUk0AAG2YAABzjgAA4AwAAINwAAB5AgAAxiwAADQWAAAcfHAMaSAAABDXSURBVHja3FlplFTVud3nnDvV0FXVUzVFDzRjA2mCgopDiGgcojjEaFSCCepTgjwDNhrj8IQYZxfxIYiuPM2LE2JEZDCDA1HAIYZJGwSaSXpuuqu7a65bdzjnvB9ViKhpXCF/Xu5a90/de+4Z9vftb3+7iJQS/+gi9xCMMkNqoAvY8nLc2bjxfUw+4wysf2ut+svrLvMLCCIBgn/BJSWg6ir1lBQpqZ6YDZFfVzYD3P/EkswPr7nZOvzq142fu2YuFv9mMUonAsYeoGM4gBTANGDQfqDfA3g7gKoaoHEcAL0wMAcYuwFWAvhbAUSAGADbAGAB1R1AzgdEtwFo/+q89DSgagQQ6wKsCGDrADxA+R5AqQS6JBBWgOLPgD3bABmTA54DHfDpe8CdVbWz7z+x8iHfWcB7+18HAEw57xL3khk32P29YFKCAMd/Wzac0eMqvrV0WvhZbwkr4S6E64JURMpw5vk/cgYCo+GtBizesBhExf/7Sxnw6XcIjGS80uOYI4oCQCbV/XlAX3TF9Xa2P0FXL39FF0QSw4Bk7DhWQiCYwv2l6WgddGlIAmllQU757hSrtLxC/CMwFn64EIveXXQk4v+tAZEABySXkmsqwbtbVyMaewDlxYMxfsIkd/hDT2bGTZjkfvTeG+q2Dz9QY7EM1T2Q/ywkUkI6EhwSEhJgCuTUK2Ycpio0tmzFK1tWIh5PQN2iwvSaeCn0EuDJU8+/PyAkD4oUgMIUdETjWPf3tZj2/VkAIP3FJbh6ZoN59cwG00om2W8fv8/47WMLvR5vHhTOIRwXjiDgBYCJSqAoChRCv1p7pAQgCtRuQo6fOD57ypTv2y6A+U/dgSc3LUSSc8gkgDUAggBuBmB84RsCcHnhOy4AFXBFYSsSsO3Ci6yQczT/rhT5/QoBSFIYW/jWsSJMAhAckPzIOC4AKgBCAe4W1kSOF5DCJqQECAEoAV595wVcOvkn2LHjU6xd+7osGatpY0fUlFVHJvZMv7Eht+K5p41kPEEcimxJJFg0uiowribIqnUQLWa7yX091t79bfHPRNomuvZVohECxHKkTQPMrT+1ZlTj+w9Goj3NyoFP32wP+b2fJs2UA+1LQXN4rQQoLipmk8aVjhpc6Rnqcaje6+ZiTTK182/RnmgooKvjxw4qbnZaes2om4feBUZWRXxCc9HZGs3URiKeC4tC9YO9WiRnu9YBf2r35ni0Fdz8+vMRgFBUjBoaDpxQU/StwX497FIhupRM+1YS39Xd228NqxoUKOagrXsPxf81gAhASgkJ4CcX3YD2ti5cd91MdDu79FXTq59ta4yll408a/aLM19zb7r1nsyv7r1N++kFo666olrMzDkZvcdx4g6IE6DCHxmjFR/KDG5a3Jh9cOfOnt0eBs8XQ82ypT1q7OChs2v8D4fF38Z3rXgzVaxQZ2FpaZDQQX13KOGfL9t0YMuXQ1ShBuZOGXP2bM29M8nSysGuvo6065pn+jxltcN8Fdsqh61bp3j/ON8nH9m5TbtqR6vbAwpAJbj33IpfwrG1xrOq189m5v2ZVMKTSJhJVWFapMJXun3I0Ddmdx+at7urP/vl86mtCGuPThgyb1IydWlzLtvX2tnfrVLGakJGJKyF9aXBkofGB4MnlKjxyo7z9HmFHPrnATlMWabl4sxxp+MH370OW7Z+jOFTAtrzVcNfzPYeiiyJielXTqx3D+zaoezc3+T+/OKht10Q6r/+vsbUox93Ou/lckhISQRlUisvUauvrfPPXDzJeG0uLZu2q7F3u67mM0VICIMqnl8NlYv/2t6y6dkW99p4wu2SAC+q61f/o9q6ZalmrDjgKz3jI6WvE97CDmyCBaH6H97S1/Xk/Oa+h1e69ktuTvZ5IEWCU22oV6u7Jxycf3sRLjZbEwzlQkX9YdlLkI3HvGcT8aN6Eb9ofrTv8Y0Z/pc+zU16bcpG+u2xi4LZ/1n83cDjF2fSN+babWAIgBBQoRVj5ek1v5N799ZfnzAbPs46m4soTE4AoRL/WT7/eXeWBB/wxOOeTfH4B0qeKI8PECkA7goYuo4FP1sKIYEPO9Z6fz0k+lLswKGKeXvMGbQD/avW/6bk6fh99uiJFeNuPgmzZn2SnLNjp/1myIUaOCKvzWS31X9/D5+jTWZL5o6i985qUa+UKUcAACfEjaSzNa+k5coHttkNwSi3vQRMcBCa5dYr1YGGU+zcxGvC7NqPTsSDtBigOeAEJVI6u6/vkQXRvgcWdeaW+EcC5QAqckBnXFibU7ntP9fZtctd+ZcRmUyd9FMXhVpCXIAzxiNtHTU/VbyXr+yzXotoQF8Y0PcIfHQw+cGdp4ZnPZfKvTzpNF/dhi57D0bm69dNPDIt3H5w8tRE7nuNUfsAtYDaUiCnAl1+mXypNflqyqtHX+nvXy9V4wMuiDjWedNjUxaBaZn40feuR+euLtx6748DEz/8/avxtu7yW/c61/G9dg/N2HomlyXSAD+r1nP+noTT3NQmNpQ40CmDAgZauJlGoRX1uO6KZvG7KoPUVZWpI1wOl+RLAM1S1Xo9Spf7otzUKDRQMKqCuv22R+9WrCa/Z+NoISZVD1cxKAKwDHCu9J7dl025qzvF8/JtwO3M1zwpke9NuoCWHZn0CqY+DUUXkPKo8qpxGDuZ3vRXNfdnsxZw/QAjQNIGUh3AJ13WJ3FNS3wrKb6NLYDxOhDeYuD8jLhyreO81thqHcBBgLB8gScEcFOA2ABs2JbasClYvE7j0vgmKmtgQHg+Qxgo7ICLdw686Lsq/f4rdk+spGFf7hrSluvUCRQwgFJIYjA6SBPVrTG7FRnXAsuXXEIAx8533ZkMCHegpNNul+VSu1gRgwXNaysqJYsJFuvps1o1CeWogq0ALRsPGNG4iHlVPSAJg9YL1LQCtS5Gt1lOSyLgJoJTAMYAywtIFTD6AN0FSBw4aGKfqelJIiX9oiigQrJ2w7uvt03k5DagfQfAeoFQETBkOODXRC7jsqRBcqW4FMgFATWnqyVurnof1O00AegEMLrzB2p5Aa4BoUmAKHfQRpVdACXkG+isASnr5JGnwdnbBmmrfN/m7f5f+61lZX2xyfuZsYl08axMCfZFxSMhkHOF41G0ACF5PSklwDnws1vvMseOO8k91NlMX37mCSXB+xkEgS2Fc1hgEwliSVgOFzb50tIJBdwsp/3dHNmSHKaefSnCrBS/X/UkuBCEOFRmqwBnQt7yiPUARVnAK4CiIJDjgA4qhZBfCUMpgayqZNR2gO0DBAOqMkDNcID7gJwjuMlhwyUELQBOA4QiCM9KMMlEqRfwaPlxaStvvZjVgF0H8AxALCal+GZ9yIAZcm50Asx4joSCxaE7eHLlwU93+y7vY+cEKC25aVzRbVmD5T4X6QSEWBKfJt2dY3zKaG/A8LsOOCFALgsSjlSKKVMvs6++scFatOrt1Blnjqm0khnWYZK2QrFDobBzScC/ziOjFJCuINy2MWTEUNz908egDYtgazbVVOXx1FTt9XqVBwDtdcC7D5BZIJoC2vuB7hhQ4/cM09NWQJKjuVxKgDvCragHhl8CjLgIqKgCRAJAEiBWoVcp9C/sVcB6x7I7GG0dSUl9NEvRFgO6w0DOBbytgPEeoD0IlG30YijBGO5wCXLspnlAQCpGVIMVF6VPaus8t6Wr3VzwmTsrtiXxycKouG9qkbju5DHFZ2RdfC7QDQFlY3vmbUGZesOY0BwrqJsZC1nThG2mTQ5AtqVjYvWeZ40raP/tG7n7Rl9GtAgnT25S5purAR1LQqSTFmpqa6+hMx1L5jyPjcJZLxyhzBhcMiPnZchqhf5AAroBoAioOX2QOt2VM+2UySQhRx+MKNyF+SUvNKnkSJ8jROE3CtAhQJbbWJPLrZgqlCtO+U642jMRKCrOzyk4wMuATBVwzviqU7/d0XdeDiR33BnSt68L/JAZ3EyV7Q/stxpoB+8NCXi27YytW5kjKxoqlYe8g/1F3M134oxBSbWmuha0m7d/J8CvXjR50PKLJ9dOPfWM8Mho6uPaP71zf/1bT0+7ZvKqZet2t7el/zw0fM+ipc+mamqHCDOb37oQx5bhmuIjry191v+HZ57QLxx2DqZfeH3PApG6awbh/7Vw2ojZQ0aUe4jPAPfqMMoCuGDyyNoXq8PP25lMacpfdIhJyb64c1EAb6BLHHbTXMCpBnLnAytZ/6vvUrHheeJdPX3YsJN9viCkVwe8BgZVlLNfXHXChXcR64ndHs8eRhQQeWwRNWANee7RRd5fXlit9SrGQRkzoxqgg4F4E1z8fn/6N5Pq/Guuryua93ifOb+Icz8I4HWhNH0SXz8zqV99SYU645yQ55ZgRNesvW9Ju+lP3Ou4/U9J+4XVMXvZ/06/LX3B2T8mJ645PTXr6vMU4cbz9sMxGlWASMOviUfvnuPb8PYqbfI5l1vv2cNX3mjvTd7k2Lf+obT4slhkUDsIsQyJYl8qWfVGZ+v7m8uKn7lN0+5y04X/HEJH7CEIMrCF9IUMQQ7ARqArbjkNI3tn3xD0zZ2R8y26JlDZkxmp9FACFnRFxGzt9M83Yw+NL4uMjWR5uTSEPC5APD4qJSCZkAqlR3heZVDSzZnux8qNBY+E2FMfji556+PG6N88KjySAH5dp26/tnvbafVz17Q0lwWLHYMrXEkTI5XivC/u01w37OCZzSsxbvJUDK2q5YtWrHWX/PpK7sTbyLFWLbgkkAQzZs4zA+VhfNa0Ra0fPF68xRJv3xlLb6x00mMG2claRThamqjxdsGbDpX4Wz2Ma7eL7IyeRK4fBwGcAkArUJU8dj/2ufeVAfA+wMoZ5KSQuVzlD68j7jNVVmJsKGtXSEBEKevs1OSuhKco0S7Md9KphNbcY/PjAkRCgOSj56jwkQTwCWgf746vW3Ny+cq5YTxyc8R3sdWRyZJSnd12Tt2vxpiZUz3CTmVCQcoEcwSVIJKAKoQKB8SvKcb9vdvve/S5O9586qblCJVVwF8ZcmRvK4EcwNCTACMURFPx3G+f8Cx5YVX6rBkXmW+/eMu0mQcCN1uKP2sLzm3uSkkAJijRFYUwSpkH8P61nC57mO1YGqmS6PIBcABIQiBABhSlguRLsgC8GhC+APCEferi2jFLKrp7x9ogOYs7wpUeCQCqZERXFUopYQpj4vag07Cr2O4+Xi+LOIxyG9T+SthSEG+ck+cOph+bMCqw8oq60M+e7sw+6rNdbXXzrk3vF7stsk11FU2HSH81/hghSqtwOg/sfxVzLrgHZbVhZH1py9UMk/NMnrpYXjJTmu9lAIBTIhJWzrnox9dYFZZP/mLm5f5B46qzCW1/S6NurOQKcY52HY+gywC1ldEm2ZmXs/AD0gZsUEel1FaIhELzAUfY0YzlKIrNKeUIAsIC3DSQ68+SFz/bsd5vWnsEIW6h6/ryvIQRItu5lUL2G/0H8Y9zdUIlCfmqtWFUgZ5qtfdS92h3lUhIi0L6h+jDPdQtOumUSzZ/un4z+8G8ORl1jOGu6HgBjcs2Q/uS9SwB6F4g2gz0bwDuvutuTD77TPz3H/+zWJotw3NNekt92cnmpr+/q4+p+7bb3LyfZbIZIiWEd5hRqRoiiGigafmftsZdwcnGv6xSzZBwH2t80Ek3xcDUr88s1QBSCaDrdQA+AOMBTQNOrDZGSC5YS6+9hxTs82wN4A7OjzMyhI2Le+p7XKtvdxtvRxLADoD4gCGX5KOaD+BQMQq0UsB+E5B9A3Pj/w0AjyJG4NHQ2H8AAAAASUVORK5CYII=" alt="Kohana" onclick="debugToolbar.collapse()">

		<!-- Kohana icon -->
		<?php if (Kohana::$config->load('debug_toolbar.minimized') === TRUE): ?>
			<ul id="debug-toolbar-menu" class="menu" style="display: none">
		<?php else: ?>
			<ul id="debug-toolbar-menu" class="menu">
		<?php endif ?>

			<!-- Kohana version -->
			<li>
				<?php echo HTML::anchor("http://kohanaframework.org", Kohana::VERSION, array('target' => '_blank')) ?>
			</li>

			<!-- Benchmarks -->
			<?php if (Kohana::$config->load('debug_toolbar.panels.benchmarks')): ?>
				<!-- Time -->
				<li id="time" onclick="debugToolbar.show('debug-benchmarks'); return false;">
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAKrSURBVDjLpdPbT9IBAMXx/qR6qNbWUy89WS5rmVtutbZalwcNgyRLLMyuoomaZpRQCt5yNRELL0TkBSXUTBT5hZSXQPwBAvor/fZGazlb6+G8nIfP0znbgG3/kz+Knsbb+xxNV63DLxVLHzqV0vCrfMluzFmw1OW8ePEwf8+WgM1UXDnapVgLePr5Nj9DJBJGFEN8+TzKqL2RzkenV4yl5ws2BXob1WVeZxXhoB+PP0xzt0Bly0fKTePozV5GphYQPA46as+gU5/K+w2w6Ev2Ol/KpNCigM01R2uPgDcQIRSJEYys4JmNoO/y0tbnY9JlxnA9M15bfHZHCnjzVN4x7TLz6fMSJqsPgLAoMvV1niSQBGIbUP3Ki93t57XhItVXjulTQHf9hfk5/xgGyzQTgQjx7xvE4nG0j3UsiiLR1VVaLN3YpkTuNLgZGzRSq8wQUoD16flkOPSF28/cLCYkwqvrrAGXC1UYWtuRX1PR5RhgTJTI1Q4wKwzwWHk4kQI6a04nQ99mUOlczMYkFhPrBMQoN+7eQ35Nhc01SvA7OEMSFzTv8c/0UXc54xfQcj/bNzNmRmNy0zctMpeEQFSio/cdvqUICz9AiEPb+DLK2gE+2MrR5qXPpoAn6mxdr1GBwz1FiclDcAPCEkTXIboByz8guA75eg8WxxDtFZloZIdNKaDu5rnt9UVHE5POep6Zh7llmsQlLBNLSMTiEm5hGXXDJ6qb3zJiLaIiJy1Zpjy587ch1ahOKJ6XHGGiv5KeQSfFun4ulb/josZOYY0di/0tw9YCquX7KZVnFW46Ze2V4wU1ivRYe1UWI1Y1vgkDvo9PGLIoabp7kIrctJXSS8eKtjyTtuDErrK8jIYHuQf8VbK0RJUsLfEg94BfIztkLMvP3v3XN/5rfgIYvAvmgKE6GAAAAABJRU5ErkJggg==" alt="time">
					<?php echo round(($benchmarks['application']['total_time'])*1000, 2) ?> ms
				</li>
				<!-- Memory -->
				<li id="memory" onclick="debugToolbar.show('debug-benchmarks'); return false;">
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAGvSURBVDjLpZO7alZREEbXiSdqJJDKYJNCkPBXYq12prHwBezSCpaidnY+graCYO0DpLRTQcR3EFLl8p+9525xgkRIJJApB2bN+gZmqCouU+NZzVef9isyUYeIRD0RTz482xouBBBNHi5u4JlkgUfx+evhxQ2aJRrJ/oFjUWysXeG45cUBy+aoJ90Sj0LGFY6anw2o1y/mK2ZS5pQ50+2XiBbdCvPk+mpw2OM/Bo92IJMhgiGCox+JeNEksIC11eLwvAhlzuAO37+BG9y9x3FTuiWTzhH61QFvdg5AdAZIB3Mw50AKsaRJYlGsX0tymTzf2y1TR9WwbogYY3ZhxR26gBmocrxMuhZNE435FtmSx1tP8QgiHEvj45d3jNlONouAKrjjzWaDv4CkmmNu/Pz9CzVh++Yd2rIz5tTnwdZmAzNymXT9F5AtMFeaTogJYkJfdsaaGpyO4E62pJ0yUCtKQFxo0hAT1JU2CWNOJ5vvP4AIcKeao17c2ljFE8SKEkVdWWxu42GYK9KE4c3O20pzSpyyoCx4v/6ECkCTCqccKorNxR5uSXgQnmQkw2Xf+Q+0iqQ9Ap64TwAAAABJRU5ErkJggg==" alt="memory">
					<?php echo text::bytes($benchmarks['application']['total_memory']) ?>
				</li>
			<?php endif ?>

			<!-- Queries -->
			<?php if (Kohana::$config->load('debug_toolbar.panels.database')): ?>
				<li id="toggle-database" onclick="debugToolbar.show('debug-database'); return false;">
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAEYSURBVBgZBcHPio5hGAfg6/2+R980k6wmJgsJ5U/ZOAqbSc2GnXOwUg7BESgLUeIQ1GSjLFnMwsKGGg1qxJRmPM97/1zXFAAAAEADdlfZzr26miup2svnelq7d2aYgt3rebl585wN6+K3I1/9fJe7O/uIePP2SypJkiRJ0vMhr55FLCA3zgIAOK9uQ4MS361ZOSX+OrTvkgINSjS/HIvhjxNNFGgQsbSmabohKDNoUGLohsls6BaiQIMSs2FYmnXdUsygQYmumy3Nhi6igwalDEOJEjPKP7CA2aFNK8Bkyy3fdNCg7r9/fW3jgpVJbDmy5+PB2IYp4MXFelQ7izPrhkPHB+P5/PjhD5gCgCenx+VR/dODEwD+A3T7nqbxwf1HAAAAAElFTkSuQmCC" alt="queries">
					<?php echo isset($queries) ? $query_count : 0 ?>
				</li>
			<?php endif ?>

			<!-- Vars -->
			<?php if (Kohana::$config->load('debug_toolbar.panels.vars')): ?>
				<li id="toggle-vars" onclick="debugToolbar.show('debug-vars'); return false;">
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAFWSURBVBgZBcE/SFQBAAfg792dppJeEhjZn80MChpqdQ2iscmlscGi1nBPaGkviKKhONSpvSGHcCrBiDDjEhOC0I68sjvf+/V9RQCsLHRu7k0yvtN8MTMPICJieaLVS5IkafVeTkZEFLGy0JndO6vWNGVafPJVh2p8q/lqZl60DpIkaWcpa1nLYtpJkqR1EPVLz+pX4rj47FDbD2NKJ1U+6jTeTRdL/YuNrkLdhhuAZVP6ukqbh7V0TzmtadSEDZXKhhMG7ekZl24jGDLgtwEd6+jbdWAAEY0gKsPO+KPy01+jGgqlUjTK4ZroK/UVKoeOgJ5CpRyq5e2qjhF1laAS8c+Ymk1ZrVXXt2+9+fJBYUwDpZ4RR7Wtf9u9m2tF8Hwi9zJ3/tg5pW2FHVv7eZJHd75TBPD0QuYze7n4Zdv+ch7cfg8UAcDjq7mfwTycew1AEQAAAMB/0x+5JQ3zQMYAAAAASUVORK5CYII=" alt="vars">
					vars
				</li>
			<?php endif ?>

			<!-- Ajax -->
			<?php if (Kohana::$config->load('debug_toolbar.panels.ajax')): ?>
				<li id="toggle-ajax" onclick="debugToolbar.show('debug-ajax'); return false;" style="display: none">
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAALvSURBVBgZBcFNaNUFAADw3//jbe/t6d6cc2/kUpeXsEgUsSSiKIzAQxDdvCgdulgagmBXLx4K7BgRWamnOgSDIj3EusRangwlbVvOyba25tvH23v/z36/oCxLcOr7uaO48sxA9Vg7LbTTQloUtrKihXUsI8cqVvAtfo4Biix78eDItmPnX90FADaTotFOisZqJx9NUta7udnlDT/+vXkc52KAIsua/T0BmHuSqwSBOCCK6a2E9vSGojBUiTg0WvNUoz74xeTjT0OAPE376zFZwXoSaKU86dLq0OqwssXSRg4uXn/o2Fjd80OVXTFAnqaD23tCm102O7kwDMSIIsKISCAKKBDka36bXnX7YetxDJAnSbNRi7S2Mu1uKQxLUUiYB6KQSCmKUEYW17o+u/lgDadigCxJ9jb7K1qdUgYlUR4IS+RsPfhFliaeGzkhr+SyJBv74aOX/wsB8qS7d6TRazMpBSFREAjWH0lmflV21lR7e/T19fl3acmbAw+9MzT7CQRlWXrr0k+1OArb3104bvKfVKEE6fSEffv2mZ+f12w2hWFodnbW6Oio8fFxRVHUY8i6ya56vSoMKKAkCAi279bpdCwvL5uYmFCr1Rw4cEC73Vav1786c+ZMO4Q86fbFCnFIFAYEoY17tzSiTcPDw+7fv+/1kxe9e/q8R/PzRkZG7N+///Tly5fL+JVz14dw6eizeyyslWYXc/UqnVZLFEWazabh4WG1Kv19lGVgfX3d3Nyc6elpcZ4kb+DEH3dnrG7FNrqlNC8V2UEjG/MGBxeMjY2ZHP/aVFDa8/RuKysr7ty58yUuxHmaHn77tRdqH598CQDkJde+mcKAhYUFRw4f1Ol0zMzMaDQa8F6tVns/ztN0ZmG55drNuwa21Qz0Vw3UezXqvQYGh1y9etUHH5419fukxcVFy2XTrVufl1mW3bxx40YeHDp5ZQjnsBc7sRM7sAONak+lUq1WHKrds7S05M/yyF84efva2Sn4HxcNUm7wsX3qAAAAAElFTkSuQmCC" alt="ajax">
					ajax (<span>0</span>)
				</li>
			<?php endif ?>

			<!-- Files -->
			<?php if (Kohana::$config->load('debug_toolbar.panels.files')): ?>
				<li id="toggle-files" onclick="debugToolbar.show('debug-files'); return false;">
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAIpSURBVDjLddM9aFRBFIbh98zM3WyybnYVf4KSQjBJJVZBixhRixSaShtBMKUoWomgnaCxsJdgIQSstE4nEhNREgyoZYhpkogkuMa4/3fuHIu7gpLd00wz52POMzMydu/Dy958dMwYioomIIgqDa+VnWrzebNUejY/NV6nQ8nlR4ufXt0fzm2WgxUgqBInAWdhemGbpcWNN9/XN27PPb1QbRdgjEhPqap2ZUv5+iOwvJnweT1mT5djZKjI6Ej/udz+wt1OJzAKYgWyDjJWyFghmzFsbtcY2gsTJwv09/Vc7RTgAEQgsqAKaoWsM8wu/z7a8B7vA8cHD3Fr+ktFgspO3a+vrdVfNEulJ/NT4zWngCBYY1oqSghKI465fvYwW+VAatPX07IZmF7YfrC0uDE8emPmilOFkHYiBKxAxhmSRPlZVVa2FGOU2Ad2ap4zg92MDBXJZczFmdflx05VEcAZMGIIClZASdesS2cU/dcm4sTBArNzXTcNakiCb3/HLRsn4Fo2qyXh3WqDXzUlcgYnam3Dl4Hif82dbOiyiBGstSjg4majEpl8rpCNUQUjgkia0M5GVAlBEBFUwflEv12b/Hig6SmA1iDtzhcsE6eP7LIxAchAtwNVxc1MnhprN/+lh0txErxrPZVdFdRDEEzHT6LWpTbtq+HLSDDiOm2o1uqlyOT37bIhHdKaXoL6pqhq24Dzd96/tUYGwPSBVv7atFglaFIu5KLuPxeX/xsp7aR6AAAAAElFTkSuQmCC" alt="files">
					files
				</li>
			<?php endif ?>

			<!-- Modules -->
			<?php if (Kohana::$config->load('debug_toolbar.panels.modules')): ?>
				<li id="toggle-modules" onclick="debugToolbar.show('debug-modules'); return false;">
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAHhSURBVDjLpZI9SJVxFMZ/r2YFflw/kcQsiJt5b1ije0tDtbQ3GtFQYwVNFbQ1ujRFa1MUJKQ4VhYqd7K4gopK3UIly+57nnMaXjHjqotnOfDnnOd/nt85SURwkDi02+ODqbsldxUlD0mvHw09ubSXQF1t8512nGJ/Uz/5lnxi0tB+E9QI3D//+EfVqhtppGxUNzCzmf0Ekojg4fS9cBeSoyzHQNuZxNyYXp5ZM5Mk1ZkZT688b6thIBenG/N4OB5B4InciYBCVyGnEBHO+/LH3SFKQuF4OEs/51ndXMXC8Ajqknrcg1O5PGa2h4CJUqVES0OO7sYevv2qoFBmJ/4gF4boaOrg6rPLYWaYiVfDo0my8w5uj12PQleB0vcp5I6HsHAUoqUhR29zH+5B4IxNTvDmxljy3x2YCYUwZVlbzXJh9UKeQY6t2m0Lt94Oh5loPdqK3EkjzZi4MM/Y9Db3MTv/mYWVxaqkw9IOATNR7B5ABHPrZQrtg9sb8XDKa1+QOwsri4zeHD9SAzE1wxBTXz9xtvMc5ZU5lirLSKIz18nJnhOZjb22YKkhd4odg5icpcoyL669TAAujlyIvmPHSWXY1ti1AmZ8mJ3ElP1ips1/YM3H300g+W+51nc95YPEX8fEbdA2ReVYAAAAAElFTkSuQmCC" alt="modules">
					modules
				</li>
			<?php endif ?>

			<!-- Routes -->
			<?php if (Kohana::$config->load('debug_toolbar.panels.routes')): ?>
				<li id="toggle-routes" onclick="debugToolbar.show('debug-routes'); return false;">
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAHYSURBVDjLlVLPS1RxHJynpVu7KEn0Vt+2l6IO5qGCIsIwCPwD6hTUaSk6REoUHeoQ0qVAMrp0COpY0SUIPVRgSl7ScCUTst6zIoqg0y7lvpnPt8MWKuuu29w+hxnmx8dzzmE5+l7mxk1u/a3Dd/ejDjSsII/m3vjJ9MF0yt93ZuTkdD0CnnMO/WOnmsxsJp3yd2zfvA3mHOa+zuHTjy/zojrvHX1YqunAZE9MlpUcZAaZQBNIZUg9XdPBP5wePuEO7eyGQXg29QL3jz3y1oqwbvkhCuYEOQMp/HeJohCbICMUVwr0DvZcOnK9u7GmQNmBQLJCgORxkneqRmAs0BFmDi0bW9E72PPda/BikwWi0OEHkNR14MrewsTAZF+lAAWZEH6LUCwUkUlntrS1tiG5IYlEc6LcjYjSYuncngtdhakbM5dXlhgTNEMYLqB9q49MKgsPjTBXntVgkDNIgmI1VY2Q7QzgJ9rx++ci3ofziBYiiELQEUAyhB/D29M3Zy+uIkDIhGYvgeKvIkbHxz6Tevzq6ut+ANh9fldetMn80OzZVVdgLFjBQ0tpEz68jcB4ifx3pQeictVXIEETnBPCKMLEwBIZAPJD767V/ETGwsjzYYiC6vzEP9asLo3SGuQvAAAAAElFTkSuQmCC" alt="routes">
					routes
				</li>
			<?php endif ?>

			<!-- Custom data -->
			<?php if (Kohana::$config->load('debug_toolbar.panels.customs')): ?>
				<li id="toggle-customs" onclick="debugToolbar.show('debug-customs'); return false;">
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAIMSURBVDjLpVNLaxNRFP6STmaKdFqrgYKWlGLSgoiKCwsKVnFRtBsVUSTNyj/gxv4Bl678AyKCoCulgmtd+W7romgzKT4QMW1G+5hMpnPnnuuZm6ZNawoVBw7n3pn5vvP4zkkopfA/j9F8cafO3FekCjGpIgKIvayftTXOkr71jkz2/UXA4HxXfz72gIx/lBsWSfiVtwiWHK8B3kRQeX/6lmnnkuDAwn0MJSKQEFChQCp9CcHixxgsGWw3B01uRKfx9t1HIP1POpoSdUulLyD0vqO26IAkDW7tgSZYeHPqcmpXxkTChKzOaAKSEdo6jnEWVY5ehFxdHs2cn55rScDR73H6DKyyRWs1R0haGdR+z8YZ3MyMTj9rpUKi/PLkUJuZfmX3nkNYmQBxzYprpyCA2XMRrvNAcdfDhgKkm6ttKTdW6jH4w4RpD/ALAaNzhH2kSwALoSJCd9+VhIqEVVeD4C1MclaOT0Ke0Cowq+X9eLHapLH23f1XreDzI27LfqT2HIfvzsRAyLB2N1coXV8vodUkfn16+HnnvrPDhrmXsxBY+fmOwcVlJh/IFebK207iuqSShg0rjer8B9TcWY7q38nmnRstm7g1gy9PDk2129mjinjy3OIvJjvI4PJ2u7CJgMEdUMmVuA9ShLez14rj/7RMDHzNAzTP/gCDvR2to968NSs9HBxqvu/E/gBCSoxk53STJQAAAABJRU5ErkJggg==" alt="customs">
					customs
				</li>
			<?php endif ?>

			<!-- Swap sides -->
			<li onclick="debugToolbar.swap(); return false;">
				<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAABjSURBVCjPY/zPgB8wMVCqgAVElP//x/AHDH+D4S8w/sWwl5GBgfE/MSYU/Ifphej8xbCLEaaAOBNS/yPbjIC3iHZD5P9faHqvk+gGbzQTYD76TLQbbP//hOqE6f5AvBsIRhYAysRMHy5Vf6kAAAAASUVORK5CYII=" alt="align">
			</li>

			<!-- Close -->
			<li class="last" onclick="debugToolbar.close(); return false;">
				<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAIhSURBVDjLlZPrThNRFIWJicmJz6BWiYbIkYDEG0JbBiitDQgm0PuFXqSAtKXtpE2hNuoPTXwSnwtExd6w0pl2OtPlrphKLSXhx07OZM769qy19wwAGLhM1ddC184+d18QMzoq3lfsD3LZ7Y3XbE5DL6Atzuyilc5Ciyd7IHVfgNcDYTQ2tvDr5crn6uLSvX+Av2Lk36FFpSVENDe3OxDZu8apO5rROJDLo30+Nlvj5RnTlVNAKs1aCVFr7b4BPn6Cls21AWgEQlz2+Dl1h7IdA+i97A/geP65WhbmrnZZ0GIJpr6OqZqYAd5/gJpKox4Mg7pD2YoC2b0/54rJQuJZdm6Izcgma4TW1WZ0h+y8BfbyJMwBmSxkjw+VObNanp5h/adwGhaTXF4NWbLj9gEONyCmUZmd10pGgf1/vwcgOT3tUQE0DdicwIod2EmSbwsKE1P8QoDkcHPJ5YESjgBJkYQpIEZ2KEB51Y6y3ojvY+P8XEDN7uKS0w0ltA7QGCWHCxSWWpwyaCeLy0BkA7UXyyg8fIzDoWHeBaDN4tQdSvAVdU1Aok+nsNTipIEVnkywo/FHatVkBoIhnFisOBoZxcGtQd4B0GYJNZsDSiAEadUBCkstPtN3Avs2Msa+Dt9XfxoFSNYF/Bh9gP0bOqHLAm2WUF1YQskwrVFYPWkf3h1iXwbvqGfFPSGW9Eah8HSS9fuZDnS32f71m8KFY7xs/QZyu6TH2+2+FAAAAABJRU5ErkJggg==" alt="close">
			</li>
		</ul>
	</div>

	<!-- Benchmarks -->
	<?php if (Kohana::$config->load('debug_toolbar.panels.benchmarks')): ?>
		<div id="debug-benchmarks" class="top" style="display: none;">
			<h1>Benchmarks</h1>
			<table cellspacing="0" cellpadding="0">
				<tr>
					<th align="left">benchmark</th>
					<th align="right">count</th>
					<th align="right">avg time</th>
					<th align="right">total time</th>
					<th align="right">avg memory</th>
					<th align="right">total memory</th>
				</tr>
				<?php if (count($benchmarks)):
					$application = array_pop($benchmarks);?>
					<?php foreach ((array)$benchmarks as $group => $marks): ?>
						<tr>
							<th colspan="6"><?php echo $group?></th>
						</tr>
						<?php foreach($marks as $benchmark): ?>
						<tr class="<?php echo text::alternate('odd','even')?>">
							<td align="left"><?php echo $benchmark['name'] ?></td>
							<td align="right"><?php echo $benchmark['count'] ?></td>
							<td align="right"><?php echo sprintf('%.2f', $benchmark['avg_time'] * 1000) ?> ms</td>
							<td align="right"><?php echo sprintf('%.2f', $benchmark['total_time'] * 1000) ?> ms</td>
							<td align="right"><?php echo text::bytes($benchmark['avg_memory']) ?></td>
							<td align="right"><?php echo text::bytes($benchmark['total_memory']) ?></td>
						</tr>
						<?php endforeach; ?>
					<?php endforeach; ?>
						<tr>
							<th colspan="2" align="left">APPLICATION</th>
							<th align="right"><?php echo sprintf('%.2f', $application['avg_time'] * 1000) ?> ms</th>
							<th align="right"><?php echo sprintf('%.2f', $application['total_time'] * 1000) ?> ms</th>
							<th align="right"><?php echo text::bytes($application['avg_memory']) ?></th>
							<th align="right"><?php echo text::bytes($application['total_memory']) ?></th>
						</tr>
				<?php else: ?>
					<tr class="<?php echo text::alternate('odd','even') ?>">
						<td colspan="6">no benchmarks to display</td>
					</tr>
				<?php endif ?>
			</table>
		</div>
	<?php endif ?>

	<!-- Database -->
	<?php if (Kohana::$config->load('debug_toolbar.panels.database')): ?>
		<div id="debug-database" class="top" style="display: none;">
			<h1>SQL Queries</h1>
			<table cellspacing="0" cellpadding="0">
				<tr align="left">
					<th>#</th>
					<th>query</th>
					<th>time</th>
					<th>memory</th>
				</tr>
				<?php foreach ($queries as $db_profile => $stats):
					list($sub_count, $sub_time, $sub_memory) = array_pop($stats); ?>
				<tr align="left">
					<th colspan="4">DATABASE "<?php echo strtoupper($db_profile) ?>"</th>
				</tr>
					<?php foreach ($stats as $num => $query): ?>
					<tr class="<?php echo text::alternate('odd','even') ?>">
						<td><?php echo $num+1 ?></td>
						<td><?php echo $query['name'] ?></td>
						<td><?php echo number_format($query['time'] * 1000, 3) ?> ms</td>
						<td><?php echo number_format($query['memory'] / 1024, 3) ?> kb</td>
					</tr>
					<?php	endforeach;	?>
					<tr>
						<th>&nbsp;</th>
						<th><?php echo $sub_count ?> total</th>
						<th><?php echo number_format($sub_time * 1000, 3) ?> ms</th>
						<th><?php echo number_format($sub_memory / 1024, 3) ?> kb</th>
					</tr>
				<?php endforeach; ?>
				<?php if (count($queries) > 1): ?>
					<tr>
						<th colspan="2" align="left"><?php echo $query_count ?> TOTAL</th>
						<th><?php echo number_format($total_time * 1000, 3) ?> ms</th>
						<th><?php echo number_format($total_memory / 1024, 3) ?> kb</th>
					</tr>
				<?php endif; ?>
			</table>
		</div>
	<?php endif ?>

	<!-- Vars -->
	<?php if (Kohana::$config->load('debug_toolbar.panels.vars')): ?>
		<div id="debug-vars" class="top" style="display: none;">
			<h1>Vars</h1>
			<ul class="varmenu">
				<li onclick="debugToolbar.showvar(this, 'vars-post'); return false;">POST</li>
				<li onclick="debugToolbar.showvar(this, 'vars-get'); return false;">GET</li>
				<li onclick="debugToolbar.showvar(this, 'vars-files'); return false;">FILES</li>
				<li onclick="debugToolbar.showvar(this, 'vars-server'); return false;">SERVER</li>
				<li onclick="debugToolbar.showvar(this, 'vars-cookie'); return false;">COOKIE</li>
				<li onclick="debugToolbar.showvar(this, 'vars-session'); return false;">SESSION</li>
			</ul>
			<div style="display: none;" id="vars-post">
				<?php echo isset($_POST) ? Debug::vars($_POST) : Debug::vars(array()) ?>
			</div>
			<div style="display: none;" id="vars-get">
				<?php echo isset($_GET) ? Debug::vars($_GET) : Debug::vars(array()) ?>
			</div>
			<div style="display: none;" id="vars-files">
				<?php echo isset($_FILES) ? Debug::vars($_FILES) : Debug::vars(array()) ?>
			</div>
			<div style="display: none;" id="vars-server">
				<?php echo isset($_SERVER) ? Debug::vars($_SERVER) : Debug::vars(array()) ?>
			</div>
			<div style="display: none;" id="vars-cookie">
				<?php echo isset($_COOKIE) ? Debug::vars($_COOKIE) : Debug::vars(array()) ?>
			</div>
			<div style="display: none;" id="vars-session">
				<?php echo isset($_SESSION) ? Debug::vars($_SESSION) : Debug::vars(array()) ?>
			</div>
		</div>
	<?php endif ?>

	<!-- Ajax Requests -->
	<?php if (Kohana::$config->load('debug_toolbar.panels.ajax')): ?>
		<div id="debug-ajax" class="top" style="display:none;">
			<h1>Ajax</h1>
			<table cellspacing="0" cellpadding="0">
				<tr align="left">
					<th width="1%">#</th>
					<th width="150">source</th>
					<th width="150">status</th>
					<th>request</th>
				</tr>
			</table>
		</div>
	<?php endif ?>

	<!-- Included Files -->
	<?php if (Kohana::$config->load('debug_toolbar.panels.files')): ?>
		<div id="debug-files" class="top" style="display: none;">
			<h1>Files</h1>
			<table cellspacing="0" cellpadding="0">
				<tr align="left">
					<th>#</th>
					<th>file</th>
					<th>size</th>
					<th>lines</th>
				</tr>
				<?php $total_size = $total_lines = 0 ?>
				<?php foreach ((array)$files as $id => $file): ?>
					<?php
					$size = filesize($file);
					$lines = count(file($file));
					?>
					<tr class="<?php echo text::alternate('odd','even')?>">
						<td><?php echo $id + 1 ?></td>
						<td><?php echo $file ?></td>
						<td><?php echo $size ?></td>
						<td><?php echo $lines ?></td>
					</tr>
					<?php
					$total_size += $size;
					$total_lines += $lines;
					?>
				<?php endforeach; ?>
				<tr align="left">
					<th colspan="2">total</th>
					<th><?php echo text::bytes($total_size) ?></th>
					<th><?php echo number_format($total_lines) ?></th>
				</tr>
			</table>
		</div>
	<?php endif ?>

	<!-- Module list -->
	<?php if (Kohana::$config->load('debug_toolbar.panels.modules')):
			$mod_counter = 0; ?>
		<div id="debug-modules" class="top" style="display: none;">
			<h1>Modules</h1>
			<table cellspacing="0" cellpadding="0">
				<tr align="left">
					<th>#</th>
					<th>name</th>
					<th>rel path</th>
					<th>abs path</th>
				</tr>
				<?php foreach($modules as $name => $path): ?>
				<tr class="<?php echo text::alternate('odd','even')?>">
					<td><?php echo ++$mod_counter ?></td>
					<td><?php echo $name ?></td>
					<td><?php echo $path ?></td>
					<td><?php echo realpath($path) ?></td>
				</tr>
				<?php endforeach ?>
			</table>
		</div>
	<?php endif ?>

	<!-- Routes -->
	<?php if (Kohana::$config->load('debug_toolbar.panels.routes')):
			$r_counter = 0; ?>
		<div id="debug-routes" class="top" style="display: none;">
			<h1>Routes</h1>
			<table cellspacing="0" cellpadding="0">
				<tr align="left">
					<th>#</th>
					<th>name</th>
				</tr>
				<?php foreach($routes as $name => $route):
						$class = ($route == Request::initial()->route() ? ' current' : ''); ?>
				<tr class="<?php echo text::alternate('odd','even').$class?>">
					<td><?php echo ++$r_counter ?></td>
					<td><?php echo $name ?></td>
				</tr>
				<?php endforeach ?>
			</table>
		</div>
	<?php endif ?>

	<!-- Custom data-->
	<?php if (Kohana::$config->load('debug_toolbar.panels.customs') && count($customs) > 0):
			$r_counter = 0; ?>
		<div id="debug-customs" class="top" style="display: none;">
			<h1>Custom data</h1>
			<ul class="sectionmenu">
				<?php foreach($customs as $section => $data): ?>
				<li onclick="debugToolbar.showvar(this, 'customs-<?php echo $section ?>'); return false;"><?php echo $section ?></li>
				<?php endforeach; ?>
			</ul>
			<?php foreach($customs as $section => $data): ?>
			<div style="display: none;" id="customs-<?php echo $section ?>">
					<pre><?php echo $data ?></pre>
			</div>
			<?php endforeach; ?>
		</div>
	<?php endif ?>
</div>