import re
import sys, os


class Baseball:
    def __init__(self, filename):
        self.filename = filename
        self.playerInfo = {}
        self.playerRank = {}
        self.readfile(self.filename)

    def readfile(self, filename):
        inputFile = open(filename, "r")
        fileLine = inputFile.readline()
        while (fileLine):
            pattern = "(?P<names>([A-Za-z]*\s){2})batted\s(?P<bats>\d)\stimes\swith\s(?P<hits>\d)\shits\sand\s(?P<runs>\d)\sruns"
            match = re.match(pattern, fileLine)
            if (match):
                names = match.group("names").strip()
                bats = match.group("bats")
                hits = match.group("hits")
                runs = match.group("runs")
                self.updatePlayerStatus(names, bats, hits, runs)
            fileLine = inputFile.readline()

        inputFile.close()

        for player in self.playerInfo:
            playerBatAverage = self.calculateStatus(self.playerInfo[player][0],
            self.playerInfo[player][1], self.playerInfo[player][2])
            self.playerRank[player] = playerBatAverage

        newPlayerRank = sorted(self.playerRank, key=lambda key: self.playerRank[key], reverse=True)
        for rankPlayer in newPlayerRank:
            print(str(rankPlayer)+": "+("%.3f" %self.playerRank[rankPlayer]))

    def calculateStatus(self, bats, hits, runs):
        bats = int(bats)
        hits = int(hits)
        runs = int(runs)
        batAverage = round(hits / bats, 3)
        return batAverage

    def updatePlayerStatus(self, names, bats, hits, runs):
        # if the player information is in dictionary, we just need
        # to change the bats, hits and runs values
        if (names in self.playerInfo.keys()):
            self.playerInfo[str(names)][0] += int(bats)
            self.playerInfo[str(names)][1] += int(hits)
            self.playerInfo[str(names)][2] += int(runs)
        else:
            self.playerInfo[str(names)] = [int(bats), int(hits), int(runs)]


if (len(sys.argv) < 2):
    sys.exit(f"Usage: {sys.argv[0]} filename")
filename = sys.argv[1]

if not os.path.exists(filename):
    sys.exit(f"Error: File '{sys.argv[1]}' not found")
baseball = Baseball(filename)
