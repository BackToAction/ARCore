<?php

namespace ARCore;

use pocketmine\entity\Effect;
use pocketmine\level\Location;
use pocketmine\Player;

class MovingCheck {

    private $vars;

    public function __construct(Variables $vars){
        $this->vars = $vars;
    }

    public function runMovingChecks(Player $p, Location $to, Location $from, $yd, $xs, $zs, $movedata, $up, $inwater, $onladder, $lg){

    $jumped = ($movedata->wasonground != $p->isOnGround());

    if (!$jumped) {

        if (((time() * 1000) - $movedata->groundtime) < 600 && !$inwater) {

            $jumped = true;

        }

    }

    //****************Start Impossible Moving******************

    //Prevents bypass of packet sneak and enforcement of blocking

    //****************End Impossible Moving******************

    //
    if ($yd != 0) {//Actually move on the y axis

        if ($up && $onladder) {

            if ($yd > (($p->getAllowFlight() || ($jumped) ? 0.424 : 0.118))) {

                if ($this->vars->issueViolation($p, CheckType::VERTICAL_SPEED)) {

                    return 1;

                }

            }

        }

        if ($up) {

            if ($yd > $this->getMaxVertical($p, $inwater, $up)) {//Moving up only

                if ($this->vars->issueViolation(p, CheckType::VERTICAL_SPEED)) {

                    return 1;

                }

            }

        }

    }
    //

    //****************Start Survival Fly******************

    if ($p->isOnGround() || $p->isInsideVehicle() || $inwater || ($p->isFlying()) || $onladder) {

        $this->vars->lastGround->put($p->getName(), new XYZ($from));

    } else {

        if (!$p->getAllowFlight() && !$inwater && !$onladder) {//Ignore users that are allowed to fly. Doesn't count for the hack fly!

            if ($up) {

                $ydis = abs($lg->y - $to->getY());

                if ($ydis > $this->getMaxHight($p, $movedata)) {

                    if ($this->vars->issueViolation($p, CheckType::FLY)) {

                        //I've discovered this trick on mineplex
                        $p->setAllowFlight(false);

                        return 3;

                    }

                }

            }

        }

    }

    //****************End Survival Fly******************

    //****************Start Horizontal Speed******************
    if (((time() * 1000) - $movedata->lastmounting) > 200) {

        $ydis = abs($lg->y - $to->getY());

        if ($xs > 0 || $zs > 0) {

            $mxs = 0;

            $csneak = $p->isSneaking();

            if ($csneak) {

                if (!$movedata->wassneaking) {

                    $diff = ((time() * 1000) - $movedata->sneaktime);

                    if ($diff < 501) {//There is a known bypass....gonna fix it sometime

                        $csneak = false;

                    }

                }

            }

            $csprint = $p->isSprinting();

            if (!$csprint) {

                if (((time() * 1000) - $movedata->sprinttime) < 1001) {

                    $csprint = true;

                }

            }

            $cfly = $p->isFlying();

            if (!$cfly) {

                if (((time() * 1000) - $movedata->flytime) < 2001) {

                    $cfly = true;

                }

            }

            if ($cfly) {

                $mxs = ($p->getFlySpeed() * 5.457);

            } else if ($csprint){

                if ($jumped) {//Player is jumping/landing

                    $mxs = ($p->speed / 0.3);

                } else {

                    $mxs = ($p->speed / 0.71);

                }

            } else if ($csneak) {

                if ($jumped) {

                    $mxs = ($p->speed / 1.7);//Lucky 7!

                } else {

                    $mxs = ($p->speed / 2);

                }

            } else {

                if ($jumped) {

                    $mxs = ($p->speed / 0.60);

                } else {

                    $mxs = ($p->speed / 0.85);

                }

            }

            if ($p->hasEffect(Effect::SPEED)) {

                $level = $p->getEffect(Effect::SPEED)->getAmplifier();

                if ($level > 0) {

                    $mxs = ($mxs * ((($level * 20) * 0.011) + 1));

                }

            }

            if ($xs > $mxs || $zs > $mxs) {

                if ($this->vars->issueViolation($p, CheckType::HORIZONTAL_SPEED)) {

                    return 1;

                }

            }

        }

        if (!$p->isOnGround() && !$p->getAllowFlight() && !$inwater && !$onladder) {

            $mdis = $this->getXZDistance($to->getX(), $lg->x, $to->getZ(), $lg->z);

            if ($mdis > $this->getMaxMD($inwater, $p->isOnGround(), $p, $ydis, $movedata)) {

                //if ($this->vars->issueViolation($p, CheckType::GLIDE)) {

                    return 1;

                //}

            }

        }

    }
    //****************End Horizontal Speed******************

    //****************Start NoFall*****************
    //If flying, ignore this check
    if (!$p->isFlying()) {

        //Prevent bypassing fly checks when moving in an horiztonal motion
        if (!$inwater && !$p->getAllowFlight() && $p->isOnGround() && !$jumped && !$onladder) {//User is allowed to fly, why check it!

            //There needs to be a waaaaaaaay more efficient way to calculate this

            $m = null;

            $bx = $to->getBlockX();
            $by = $to->getBlockY();
            $bz = $to->getBlockZ();

            if ($bx != $from->getBlockX() || $bz != $from->getBlockZ()) {

                $boy = $by + 1;

                $oy = $boy;

                $con = true;

                while ($con) {

                    $boy--;

                    $m = $from->getBlock()->getRelative(0, (($oy - $boy) * -1) + 1, 0)->getType();

                    if ($m.isSolid()) {

                        $con = false;
                        break;

                    }

                    if ($boy < 0) {

                        $con = false;//Safe check for flying over bedrock...which should be impossible
                        break;

                    }

                }

                $dis = ($oy - $boy);

                if ($dis > 2) {//Prevent bypassing fly checks when moving in an horiztonal motion

                    $nearblock = false;

                    //Make sure they are not standing at the end of a block
                    for ($x = $bx - 1; $x < $bx + 1; $x++) {

                        for ($z = $bz - 1; $z < $bz + 1; $z++) {

                            if ($p->getLevel()->getBlockAt($x, ($to->getBlockY() - 1), $z)->getType()->isSolid()) {

                                $nearblock = true;
                                    break;

                            }

                        }

                    }

                    if (!$nearblock) {

                        //if ($this->vars->issueViolation($p, CheckType::FLY)) {

                            return 1;

                        //}

                    }
                }

            }

        }

        //Start nofall & fly check
        if (!$p->getAllowFlight()) {

            if ($to->getBlockY() != $from->getBlockY()) {

                if ($up && $p->isOnGround() && !$inwater) {

                    if ($p->getVelocity()->getY() < 0) {//Moving up when velocity says to go down...seems legit

                        $m = $from->getBlock()->getType();

                        if ($m != Material::CHEST && $m != Material::TRAPPED_CHEST) {

                            //if (this.vars.issueViolation(p, CheckType.NOFALL)) {

                                return 1;

                            //}

                        }

                    }

                }

            }

        }
        //End nofall & fly check

        if (!$up && $yd > 0.25 && $p->isOnGround()) { //Falling while onground? I DON'T THINK SO

            //if (this.vars.issueViolation(p, CheckType.NOFALL)) {

                return 4;

            //}

        }

    }

    //****************End NoFall******************

    //****************Start Timer******************
   /* if ($to->getX() != $from->getX() || $to->getY() != $from->getY() || $to->getZ() != $from->getZ()) {

        if (((time() * 1000) - $movedata->getTimeStart()) > 500) {

            $max = 0;

            $max = round((Utils.getPing($p) / 100));

            if (max < 0) {

                max = 0;

            }

            max = max + Settings.maxpacket;

            if (movedata.getAmount() > max) {

                //Maybe block the checkpoint exploit?
                //double xzdis = Utils.getXZDistance(movedata.lastloc.x, to.getX(), movedata.lastloc.z, to.getZ());

                if (this.vars.issueViolation(p, CheckType.TIMER)) {

                    if (movedata.getAmount() > 50) {

                        p.kickPlayer("Too many packets! Are you (or the server) lagging badly?");

                    } else {

                        p.teleport(movedata.lastloc.toLocation(to.getPitch(), to.getYaw()), TeleportCause.UNKNOWN);

                    }

                    movedata.reset(movedata.lastloc);

                }

            } else {

                movedata.reset(new XYZ(from));

            }

        } else {

            movedata.setAmount(movedata.getAmount() + 1);

        }

        this.vars.setMoveData(p.getName(), movedata);

    }*/
    //****************End Timer******************

    return 0;
}

//****************API METHODS*********************
private function getMaxHight(Player $p, MoveData $md) {

    $d = 0;

    if ($p->hasEffect(Effect::JUMP)) {

            $level = $p->getEffect(Effect::JUMP)->getAmplifier();

        if ($level == 1) {

            $d = 1.9;

        } else if ($level == 2) {

            $d = 2.7;

        } else if ($level == 3) {

            $d = 3.36;

        } else if ($level == 4) {

            $d = 4.22;

        } else if ($level == 5) {

            $d = 5.16;

        } else if ($level == 6) {

            $d = 6.19;

        } else if ($level == 7) {

            $d = 7.31;

        } else if ($level == 8) {

            $d = 8.5;

        } else if ($level == 9) {

            $d = 9.76;

        } else if ($level == 10) {

            $d = 11.1;

        } else {

            $d = ($level) + 1;

        }

        $d = $d + 1.35;

    } else {

        $d = 1.35;

    }

    if ($md->yda != 0 && ((time() * 1000) < $md->velexpirey)) {

        $d = $d + $md->yda;

    }

    return $d;

}

private function getMaxVertical(Player $p, $inwater, $up) {

    $d = 0.5;

    if ($p->hasEffect(Effect::JUMP)) {

        $d = $d + ($p->getEffect(Effect::JUMP)->getAmplifier() * 0.11);

    }

    if ($inwater && !$p->getAllowFlight()) {

        if ($up) {

            $d = 0.3401;

        } else {

            $d = abs($p->getMotion()->getY());

        }

    }

    if ($p->getMotion()->getY() > 0) {

        $d = $d + ($p->getMotion()->getY());

    }

    return $d;

}

private function getMaxMD($inwater, $onground, Player $p, $ydis, MoveData $md) {

    $d = 0;

    $csneak = $p->isSneaking();
    $csprint = $p->isSprinting();

    $now = time() * 1000;


    if (!$csneak) {

        if (($now - $md->sneaktime) <= 1000) {

            $csneak = true;

        }

    } else {

        if (!$onground) {

            if (($now - $md->sneaktime) <= 1000) {

                $csneak = false;

            }

        }

    }

    if (!$csprint) {

        if (($now - $md->sprinttime) <= 1000) {

            $csprint = true;

        }

    }

    //TODO Account jump effect

    /*if ($p->isFlying()) {

        $d = 1.30;

    }*/ if ($csprint) {

        $d = (18.3 + $d) + (8 * $ydis);

    } else if ($csneak) {

        if ($onground) {

            $d = 0.065;

        } else {

            $d = 0.67;

        }

    } else {

        $d = (5.6 + $d) + (3 * $ydis);

    }

    if ($md->mda != 0 && ((time() * 1000) < $md->velexpirex)) {

        $d = $d + $md->mda;

    }

    return $d;

}

private function getXZDistance($x1, $x2, $z1, $z2) {

    $a1 = ($x2 - $x1);

    $a2 = ($z2 - $z1);

    return (($a1 * ($a1)) + ($a2 * $a2));

}

}
