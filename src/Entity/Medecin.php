<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\MedecinRepository")
 */
class Medecin
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $matricule;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Service", inversedBy="medecins")
     * @ORM\JoinColumn(nullable=false)
     */
    private $service;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Regex(
     * pattern="/^(77|78|76|70)[0-9]{7}$/",
     * message=" Numero non valide")
     */
    private $tel;

    /**
     * @ORM\Column(type="date")
     */
    private $dateNais;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Specialite", mappedBy="medecin")
     */
    private $specialites;

    public function __construct()
    {
        $this->specialites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }
    

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(int $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getDateNais(): ?\DateTimeInterface
    {
        return $this->dateNais;
    }

    public function setDateNais(\DateTimeInterface $dateNais): self
    {
        $this->dateNais = $dateNais;

        return $this;
    }

    /**
     * @return Collection|Specialite[]
     */
    public function getSpecialites(): Collection
    {
        return $this->specialites;
    }
    public function genereSpecialite($specialites){
        if($specialites[1]){
            return strtoupper(substr($specialites[0]->getLibelle(),0,1).substr($specialites[1]->getLibelle(),0,1));
        }else{
            return strtoupper(substr($specialites[0]->getLibelle(),0,2));
        }
        
    }
    private function genereIdMatricule($ma){
        $m=$ma+1;
        if (($ma<10)){
            $mat="0000".$m;
        }
        if (($ma>=10 && $ma<100)) {
            $mat="000".$m;
        }
        if (($ma>=100 && $ma<1000)) {
            $mat="00".$m;
        }
        if (($ma>=1000 && $ma<10000)) {
            $mat="0".$m;
        }if (($ma>=10000 && $ma<100000)) {
            $mat=$m;
        }
        return $mat;
        
    }
    public function genereMatricule($gSpe,$gId){
        return "M".$gSpe.$gId;
        
    }
    
    public function addSpecialite(Specialite $specialite): self
    {
        if (!$this->specialites->contains($specialite)) {
            $this->specialites[] = $specialite;
            $specialite->addMedecin($this);
        }

        return $this;
    }

    public function removeSpecialite(Specialite $specialite): self
    {
        if ($this->specialites->contains($specialite)) {
            $this->specialites->removeElement($specialite);
            $specialite->removeMedecin($this);
        }

        return $this;
    }
}
